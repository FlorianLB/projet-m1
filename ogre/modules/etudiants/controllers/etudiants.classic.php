<?php

class etudiantsCtrl extends jControllerDaoCrud {
 
    protected $dao = 'etudiants~etudiants';
 
    protected $form = 'etudiants~etudiants';
 
    protected $listTemplate = 'etudiants~etudiant_list';
    protected $editTemplate = 'etudiants~etudiant_edit';
    protected $viewTemplate = 'etudiants~etudiant_view';

    protected $propertiesForList = array('num_etudiant', 'nom', 'prenom','date_naissance');
	
	 /**
     * Après l'insertion de l'etudiant, on l'ajoute dans tous les semestres de sa formation
     */    
    protected function _afterCreate($form, $id, $resp){
	
	$semestre = jDao::get('formations~semestre');
	$factory = jDao::get('etudiants_semestre');
	// On recupere les formations selectionné
	foreach ($form->getData('formations') as $row1) {
	// On recupere les semestres de la formation
	    $list_semestre = $semestre->getByFormation($row1);
			
	    foreach ($list_semestre as $row) {
		//On associe l'etudiant au semestres de ses formations
		$etudiant_semestre1 = jDao::createRecord('etudiants_semestre');
		$etudiant_semestre1->num_etudiant = $id;
		$etudiant_semestre1->id_semestre = $row->id_semestre;
		$etudiant_semestre1->statut = 'NOK';
		$factory->insert($etudiant_semestre1);
	    }
	}
    }
   
    public function _index($resp, $tpl){
        $resp->setTitle('Liste des etudiants');
    }
   
    public function _view($form, $resp, $tpl){
	
        $resp->setTitle('Détails de l\'etudiant');
	$factoryformation = jDao::get('formations~formation');
	$factoryetudiant_semestre = jDao::get('etudiants~etudiants_semestre_semestre');
	$formationarray = array();
	
	$liste_semestre = $factoryetudiant_semestre->getByEtudiantNum($this->param('id',0));
	
	foreach($liste_semestre as $semestre){
	    $formation = $factoryformation->get($semestre->id_formation);
	    $formationarray[$formation->id_formation] = $formation;
	}	    
	$form->deactivate('formations');
	$tpl->assign('formations', $formationarray);
    }
   
    public function _create($form, $resp, $tpl){
        $resp->setTitle('Créer un nouvel etudiant');
	$form->deactivate('formations',FALSE);
    }
   
    public function _editUpdate($form, $resp, $tpl){
	$resp->setTitle('Modifier un etudiant');
       
	$factoryformation = jDao::get('formations~formation');
	$factoryetudiant_semestre = jDao::get('etudiants~etudiants_semestre_semestre');
	$formationarray = array();
	
	$liste_semestre = $factoryetudiant_semestre->getByEtudiantNum($this->param('id',0));
	
	foreach($liste_semestre as $semestre){
	    $formation = $factoryformation->get($semestre->id_formation);
	    $formationarray[] = $formation->id_formation;
	}	    

	$form->setData('formations',$formationarray);
       
    }  
    /**
     * Suppresion des elements dependants de etudiants
     */
    protected function _delete($id, $resp) {
        jDao::get('etudiants~etudiants_semestre')->deleteByEtudiants($id);
        return true;
    }
    
}