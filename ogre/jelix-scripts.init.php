<?php
/**
* @package   ogre
* @subpackage 
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

define ('JELIX_APP_PATH', dirname (__FILE__).DIRECTORY_SEPARATOR); // don't change

require (JELIX_APP_PATH.'/../lib/jelix/init.php');

define ('JELIX_APP_VAR_PATH',     JELIX_APP_PATH.'var/');
define ('JELIX_APP_LOG_PATH',     JELIX_APP_PATH.'var/log/');
define ('JELIX_APP_CONFIG_PATH',  JELIX_APP_PATH.'var/config/');
define ('JELIX_APP_WWW_PATH',     JELIX_APP_PATH.'www/');
define ('JELIX_APP_CMD_PATH',     JELIX_APP_PATH.'scripts/');

// the temp path for jelix-scripts
define ('JELIX_APP_TEMP_PATH',    realpath(JELIX_APP_PATH.'../temp/ogre-jelix-scripts/').'/');

// the temp path for cli scripts of the application
define ('JELIX_APP_TEMP_CLI_PATH',    realpath(JELIX_APP_PATH.'../temp/ogre-cli/').'/');

// the temp path for the web scripts of the application (the same value as JELIX_APP_TEMP_PATH in application.init.php)
define ('JELIX_APP_REAL_TEMP_PATH',    realpath(JELIX_APP_PATH.'../temp/ogre/').'/');
