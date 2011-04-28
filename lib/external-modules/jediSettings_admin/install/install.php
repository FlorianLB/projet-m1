<?php
/**
* @package   jediSettings
* @author    Florian Lonqueu-Brochard
* @copyright 2010 Florian Lonqueu-Brochard
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


class jediSettings_adminModuleInstaller extends jInstallerModule {

    function install() {
        
        if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('jedi.settings.manage', 'jediSettings~acl.jedi.settings.manage');
            jAcl2DbManager::addRight(1, 'jedi.settings.manage'); // for admin group
        }
        
        if ($this->firstExec('copyfile')) {
            $this->copyDirectoryContent('www/assets/admin/images/', 'www:assets/admin/images/');
        }
        
    }
}