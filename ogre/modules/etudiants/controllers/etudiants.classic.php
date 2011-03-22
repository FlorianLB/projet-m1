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
				$factory = jDao::get('etudiants_semestre');
				$factory->insert($etudiant_semestre1);
			}
		}
    }
   
    public function _index($resp, $tpl){
        $resp->setTitle('Liste des etudiants');
    }
   
    public function _view($form, $resp, $tpl){
        $resp->setTitle('Détails de l\'etudiant');
    }
   
    public function _create($form, $resp, $tpl){
        $resp->setTitle('Créer un nouvel etudiant');
    }
   
    public function _editUpdate($form, $resp, $tpl){
       $resp->setTitle('Modifier un etudiant');
    }  
    /**
     * Suppresion des elements dependants de etudiants
     */
    protected function _delete($id, $resp) {
        jDao::get('etudiants~etudiants_semestre')->deleteByEtudiants($id);
        return true;
    }
    
}