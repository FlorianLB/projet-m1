<?php
/**
* @package   ogre
* @subpackage ogre
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/


class ogreModuleInstaller extends jInstallerModule {

    function install() {
        //if ($this->firstDbExec())
        //    $this->execSQLScript('sql/install');

        /*if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('my.subject', 'ogre~acl.my.subject');
            jAcl2DbManager::addRight(1, 'my.subject'); // for admin group
        }
        */
    }
}