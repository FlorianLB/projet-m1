<?php
/**
* @package     jelix
* @subpackage  db
* @author     Laurent Jouanneau
* @contributor Yannick Le Guédart, Laurent Raufaste
* @copyright  2005-2010 Laurent Jouanneau
*
* API ideas of this class were get originally from the Copix project (CopixDbFactory, Copix 2.3dev20050901, http://www.copix.org)
* No lines of code are copyrighted by CopixTeam
*
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 *
 */
require(JELIX_LIB_PATH.'db/jDbConnection.class.php');
require(JELIX_LIB_PATH.'db/jDbResultSet.class.php');

/**
 * factory for database connector and other db utilities
 * @package  jelix
 * @subpackage db
 */
class jDb {

    /**
     * @var array list of profiles currently used
     */
    static private $_profiles = null;
    
    /**
     * @var array list of opened connections
     */
    static private $_cnxPool = array();

    /**
    * return a database connector. It uses a temporay pool of connection to reuse
    * currently opened connections.
    * 
    * @param string  $name  profile name to use. if empty, use the default one
    * @return jDbConnection  the connector
    */
    public static function getConnection ($name = null) {
        $profile = self::getProfile ($name);

        // we set the name to avoid two connections for a same profile, when the given name
        // is an alias of a real profile and when we call getConnection several times,
        // with no name, with the alias name or with the real name.
        $name = $profile['name'];

        if (!isset(self::$_cnxPool[$name])) {
            self::$_cnxPool[$name] = self::_createConnector($profile);
        }
        return self::$_cnxPool[$name];
    }

    /**
     * create a new jDbWidget
     * @param string  $name  profile name to use. if empty, use the default one
     * @return jDbWidget
     */
    public static function getDbWidget ($name = null) {
        $dbw = new jDbWidget(self::getConnection($name));
        return $dbw;
    }

    /**
    * instancy a jDbTools object. Use jDbConnection::tools() instead.
    * @param string $name profile name to use. if empty, use the default one
    * @return jDbTools
    * @deprecated since 1.2
    */
    public static function getTools ($name = null) {
        $cnx = self::getConnection ($name);
        return $cnx->tools();
    }

    /**
    * load properties of a connector profile
    *
    * a profile is a section in the dbprofils.ini.php file
    *
    * the given name can be a profile name (it should correspond to a section name
    * in the ini file), or an alias of a profile. An alias is a parameter name
    * in the global section of the ini file, and the value of this parameter
    * should be a profile name.
    *
    * @param string   $name  profile name or alias of a profile name. if empty, use the default profile
    * @param boolean  $noDefault  if true and if the profile doesn't exist, throw an error instead of getting the default profile
    * @return array  properties
    */
    public static function getProfile ($name='', $noDefault = false) {
        global $gJConfig;
        if (self::$_profiles === null) {
            self::$_profiles = parse_ini_file(JELIX_APP_CONFIG_PATH.$gJConfig->dbProfils , true);
        }

        if ($name == '')
            $name = 'default';
        $targetName = $name;

        // the name attribute created in this method will be the name of the connection
        // in the connections pool. So profiles of aliases and real profiles should have
        // the same name attribute.

        if (isset(self::$_profiles[$name])) {
            if (is_string(self::$_profiles[$name])) {
                $targetName = self::$_profiles[$name];
            }
            else { // this is an array, and so a section
                self::$_profiles[$name]['name'] = $name;
                return self::$_profiles[$name];
            }
        }
        // if the profile doesn't exist, we take the default one
        elseif (!$noDefault && isset(self::$_profiles['default'])) {
            trigger_error(jLocale::get('jelix~db.error.profile.use.default', $name), E_USER_NOTICE);
            if (is_string(self::$_profiles['default'])) {
                $targetName = self::$_profiles['default'];
            }
            else {
                self::$_profiles['default']['name'] = 'default';
                return self::$_profiles['default'];
            }
        }
        else {
            if ($name == 'default')
                throw new jException('jelix~db.error.default.profile.unknown');
            else
                throw new jException('jelix~db.error.profile.type.unknown',$name);
        }

        if (isset(self::$_profiles[$targetName]) && is_array(self::$_profiles[$targetName])) {
            self::$_profiles[$targetName]['name'] = $targetName;
            return self::$_profiles[$targetName];
        }
        else {
            throw new jException('jelix~db.error.profile.unknown', $targetName);
        }
    }

    /**
     * call it to test a profile (during an install for example)
     * @param array  $profile  profile properties
     * @return boolean  true if properties are ok
     */
    public function testProfile ($profile) {
        try {
            self::_createConnector ($profile);
            $ok = true;
        }
        catch(Exception $e) {
            $ok = false;
        }
        return $ok;
    }

    /**
    * create a connector
    * @param array  $profile  profile properties
    * @return jDbConnection|jDbPDOConnection  database connector
    */
    private static function _createConnector ($profile) {
        if ($profile['driver'] == 'pdo' || (isset($profile['usepdo']) && $profile['usepdo'])) {
            $dbh = new jDbPDOConnection($profile);
            return $dbh;
        }
        else {
            global $gJConfig;
            if (!isset($gJConfig->_pluginsPathList_db[$profile['driver']])
                || !file_exists($gJConfig->_pluginsPathList_db[$profile['driver']])) {
                throw new jException('jelix~db.error.driver.notfound', $profile['driver']);
            }
            $p = $gJConfig->_pluginsPathList_db[$profile['driver']].$profile['driver'];
            require_once($p.'.dbconnection.php');
            require_once($p.'.dbresultset.php');

            //creating of the connection
            $class = $profile['driver'].'DbConnection';
            $dbh = new $class ($profile);
            return $dbh;
        }
    }

    /**
     * create a temporary new profile
     * @param string $name the name of the profile
     * @param array|string $params parameters of the profile. key=parameter name, value=parameter value.
     *                      same kind of parameters we found in dbprofils.ini.php
     *                      we can also indicate a name of an other profile, to create an alias
     */
    public static function createVirtualProfile ($name, $params) {
        global $gJConfig;
        if ($name == '') {
           throw new jException('jelix~db.error.virtual.profile.no.name');
        }

        if (self::$_profiles === null) {
            self::$_profiles = parse_ini_file (JELIX_APP_CONFIG_PATH . $gJConfig->dbProfils, true);
        }
        self::$_profiles[$name] = $params;
        self::$_profiles[$name]['name'] = $name; // pool name
        unset (self::$_cnxPool[$name]); // close existing connection with the same pool name
    }
    
    /**
     * clear the loaded profiles to force to reload the db profiles file.
     * WARNING: it closes all opened connections !
     * @since 1.2
     */
    public static function clearProfiles() {
        self::$_profiles = null;
        self::$_cnxPool  = array();
    }
}
