<?php
/**
* @package   ogre
* @subpackage etudiants
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class widget_exportformationZone extends jZone {
    protected $_tplname='recherche';

    protected function _prepareTpl(){
        //$this->_tpl->assign('foo','bar');
        $form = jForms::create('phpexcel~widget_exportformation');
        $this->_tpl->assign('form',$form);
        $this->_tpl->assign('submitAction', 'phpexcel~widget_exportformation');
        
    }
}
