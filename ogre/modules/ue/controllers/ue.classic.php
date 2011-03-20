<?php

class ueCtrl extends jControllerDaoCrud {
 
    protected $dao = 'ue~ue';
 
    protected $form = 'ue~ueform';
    
    protected $propertiesForList = array('code_ue', 'coeff', 'credits', 'libelle');
 
 
    /**
     * Après l'insertion de l'ue, on passe toute les ue avec le meme code en tant que non-active
     */
    protected function _afterCreate($form, $id, $resp){
        jDao::get('ue~ue')->setOldUeVersion($id, $form->getData('code_ue'));
    }
 
}