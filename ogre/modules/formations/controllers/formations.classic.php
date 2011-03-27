<?php

class formationsCtrl extends jControllerDaoCrud {
 
    protected $dao = 'formations~formation';
 
    protected $form = 'formations~formation';
 
    protected $listTemplate = 'formations~formation_list';
    protected $editTemplate = 'formations~formation_edit';
    protected $viewTemplate = 'formations~formation_view';
 
    protected $propertiesForList = array('code_formation', 'annee', 'libelle');
    
    protected function _index($resp, $tpl){
        $resp->setTitle('Liste des formations');
    }
    
    protected function _view($form, $resp, $tpl){
        
        $formation = $form->getData('code_formation');
        
        $resp->setTitle('Détails de '.$formation);
        
        
        $semestres = array();
        $factory = jDao::get('formations~ue_semestre_ue');
        foreach(jDao::get('formations~semestre')->getByFormation($this->param('id',0)) as $sem){
            $semestres[$sem->num_semestre]['ues'] = $factory->getBySemestre($sem->id_semestre);
            $semestres[$sem->num_semestre]['id'] = $sem->id_semestre;
        }

        $tpl->assign('semestres', $semestres);
    }
    
    protected function _create($form, $resp, $tpl){
        $resp->setTitle('Créer une nouvelle formation');
    }
    
    protected function _editUpdate($form, $resp, $tpl){
        $resp->setTitle('Modifier une formation');

    }
    
    protected function _afterCreate($form, $id, $resp){
        //On créer le 1er semestre
        $semestre1 = jDao::createRecord('formations~semestre');
        $semestre1->id_formation = $id;
        $semestre1->num_semestre = 1;
        
        //On créer le 2eme
        $semestre2 = jDao::createRecord('formations~semestre');
        $semestre2->id_formation = $id;
        $semestre2->num_semestre = 2;
        
        // On insère via la factory DAO
        $factory = jDao::get('formations~semestre');
        $factory->insert($semestre1);
        $factory->insert($semestre2);
        
        jMessage::add('Formation créée !');
        
        Logger::log('creation_formation', $form->getData('code_formation'),  $form->getData('annee'));
    }
    
    /**
     * Suppresion des elements dependants de formation
     */
    protected function _delete($id, $resp) {
        jDao::get('formations~semestre')->deleteByFormation($id);
        return true;
    }
    
    protected function _afterUpdate($form, $id, $resp){
        jMessage::add('Modifications effectuées !');
    }
    
 
}