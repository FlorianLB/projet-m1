<?php
/**
* @package   ogre
* @subpackage etudiants
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class rechercheZone extends jZone {
    protected $_tplname='recherche';

    protected function _prepareTpl(){
        //$this->_tpl->assign('foo','bar');
        $form = jForms::create('etudiants~recherche');
        $this->_tpl->assign('form',$form);
        $this->_tpl->assign('submitAction', 'etudiants~etudiants:recherche');
        
    }
}
