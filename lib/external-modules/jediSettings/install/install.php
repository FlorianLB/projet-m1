<?php
/**
* @package   jediSettings
* @author    Florian Lonqueu-Brochard
* @copyright 2010 Florian Lonqueu-Brochard
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


class jediSettingsModuleInstaller extends jInstallerModule {

    function install() {
        
        if ($this->firstDbExec())
            $this->execSQLScript('sql/install');

    }
}