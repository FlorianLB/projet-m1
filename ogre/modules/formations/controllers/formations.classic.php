<?php

class formationsCtrl extends jControllerDaoCrud {
 
    protected $dao = 'formations~formation';
 
    protected $form = 'formations~formation';
 
    protected $listTemplate = 'formations~formation_list';
    protected $editTemplate = 'formations~formation_edit';
    protected $viewTemplate = 'formations~formation_view';
 
    protected $propertiesForList = array('code_formation', 'annee', 'libelle');
    
    public function _index($resp, $tpl){
        $resp->setTitle('Liste des formations');
    }
    
    public function _view($form, $resp, $tpl){
        $resp->setTitle('Détails de la formation');
    }
    
    public function _create($form, $resp, $tpl){
        $resp->setTitle('Créer une nouvelle formation');
    }
    
    public function _editUpdate($form, $resp, $tpl){
        $resp->setTitle('Modifier une formation');
    }
 
}