<?php

class ueCtrl extends jControllerDaoCrud {
 
    protected $dao = 'ue~ue';
 
    protected $form = 'ue~ueform';
	
	protected $propertiesForList = array('code_ue', 'coeff', 'credits', 'libelle');
	
	protected function _beforeSaveCreate($form, $form_daorec){
        $form_daorec->last_version = 1;
    }
 
}