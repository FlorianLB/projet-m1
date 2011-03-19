<?php
/**
* @package   ogre
* @subpackage etudiants
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/


class etudiantsModuleInstaller extends jInstallerModule {

    function install() {
        //if ($this->firstDbExec())
        //    $this->execSQLScript('sql/install');

        /*if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('my.subject', 'etudiants~acl.my.subject');
            jAcl2DbManager::addRight(1, 'my.subject'); // for admin group
        }
        */
    }
}