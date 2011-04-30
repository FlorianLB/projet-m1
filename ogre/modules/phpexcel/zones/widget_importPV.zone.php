<?php
/**
* @package   ogre
* @subpackage etudiants
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class widget_importPVZone extends jZone {
    protected $_tplname='basic';

    protected function _prepareTpl(){
        //$this->_tpl->assign('foo','bar');
        $form = jForms::create('phpexcel~import_PV');
        $this->_tpl->assign('form',$form);
        $this->_tpl->assign('submitAction', 'phpexcel~import:doimport_pv');
        
    }
}
