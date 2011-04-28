<?php
/**
* @package jediSettings_admin
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class crudCtrl extends jControllerDaoCrud {
    
    protected $dao = 'jediSettings~jedisettings';
 
    protected $form = 'jediSettings_admin~jedisettings';

    protected $propertiesForRecordsOrder = array('key' => 'asc');
    
    protected $listTemplate = 'jediSettings_admin~crud_list';
    protected $editTemplate = 'jediSettings_admin~crud_edit';
    protected $viewTemplate = 'jediSettings_admin~crud_view';
    
    protected $propertiesForList = array('label', 'value', 'key');
    
    
    public $pluginParams = array(
        '*' => array( 'jacl2.right'=>'jedi.settings.manage')
    );
    
    
    protected function _create($form, $resp, $tpl) {
        $form->deactivate('last_update');
    }
    protected function _view($form, $resp, $tpl) {
        $form->deactivate('last_update');
    }
    protected function _editUpdate($form, $resp, $tpl) {
        $form->deactivate('last_update');
        $form->deactivate('key');
    }
}

