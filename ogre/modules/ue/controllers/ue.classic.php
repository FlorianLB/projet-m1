<?php

class ueCtrl extends jControllerDaoCrud {
 
    protected $dao = 'ue~ue';
 
    protected $form = 'ue~ueform';
    
    protected $listTemplate = 'ue~ue_list';
    protected $editTemplate = 'ue~ue_edit';
    protected $viewTemplate = 'ue~ue_view';
    
    protected $propertiesForList = array('code_ue', 'coeff', 'credits', 'libelle','formule');
 
 
    /**
     * 
     */
    protected function _beforeSaveCreate($form, $form_daorec){
        $form_daorec->last_version = 1;
    }
    
    /**
     * Après l'insertion de l'ue, on passe toute les ue avec le meme code en tant que non-active
     */
    protected function _afterCreate($form, $id, $resp){
        jDao::get('ue~ue')->setOldUeVersion($id, $form->getData('code_ue'));
        
        //TODO ajouté la creation des epreuve apres l'insertion de la formule
    }
 
 
    protected function _index($resp, $tpl){
        $resp->setTitle('Liste des UEs');
    }
    
    protected function _view($form, $resp, $tpl){
        
        $ue = $form->getData('code_ue');
        
        $resp->setTitle('Détails de '.$ue);
    }
    
    protected function _create($form, $resp, $tpl){
        $resp->setTitle('Créer une nouvelle UE');
    }
    
    protected function _editUpdate($form, $resp, $tpl){
        $resp->setTitle('Modifier une UE');
    }
 
}