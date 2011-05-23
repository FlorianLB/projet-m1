<?php

class widget_geishaZone extends jZone {
    protected $_tplname='widget_geisha';

    protected function _prepareTpl(){
        //$this->_tpl->assign('foo','bar');
        $form = jForms::create('etudiants~import_geisha');
        $this->_tpl->assign('form',$form);
        //$this->_tpl->assign('submitAction', 'etudiants~import:geisha');
        $this->_tpl->assign('submitAction', 'etudiants~import:doimport_geisha');
        
    }
}
