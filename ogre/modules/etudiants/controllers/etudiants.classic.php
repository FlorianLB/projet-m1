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
		// Probleme comment recuperer la valeur id_formation a partir du formulaire
		$form->getData('formations');
		foreach ($form as $row1) {
			$list_semestre = $semestre->getByFormation($row1);
			
			foreach ($list_semestre as $row) {
				//On créer le 1er semestre
				$etudiant_semestre1 = jDao::createRecord('etudiants_semestre');
				$etudiant_semestre1->num_etudiant = $form->getData('num_etudiant');
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
}