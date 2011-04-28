<?php
/**
* @package  jediSettings_admin
* @author      Florian Lonqueu-Brochard
* @copyright   2011 Florian Lonqueu-Brochard
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

class jedisettings_adminListener extends jEventListener{

    /**
    *
    */
    function onmasteradminGetMenuContent ($event) {

        if (jAcl2::check('jedi.settings.manage')) {
            $item = new masterAdminMenuItem('jediSettings', jLocale::get('jediSettings_admin~crud.adminmenu.title'), jUrl::get('jediSettings_admin~crud:index'), 200, 'system');
            $item->icon = $GLOBALS['gJConfig']->urlengine['basePath'] . 'assets/admin/images/cd.png';
            $event->add($item);
        }
    }
}
