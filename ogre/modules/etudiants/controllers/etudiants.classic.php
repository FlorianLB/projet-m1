<?php

class etudiantsCtrl extends jControllerDaoCrud {
 
    protected $dao = 'etudiants~etudiants';
 
    protected $form = 'etudiants~etudiants';
 
    protected $listTemplate = 'etudiants~etudiant_list';
    protected $editTemplate = 'etudiants~etudiant_edit';
    protected $viewTemplate = 'etudiants~etudiant_view';

    protected $propertiesForList = array('num_etudiant', 'nom', 'prenom','date_naissance');
    
    
    public function etu_semestres(){
	$rep = $this->getResponse('html');
        
        $id = $this->param('id', 0);
	$id_formation = $this->param('id_formation', 0);
	
	$semestrearray = array();
	$ueoption = array();
	
	$factorysemestre = jDao::get('formations~semestre');
	$factorysemestre_etudiant = jDao::get('etudiants~etudiants_semestre');
	
	foreach( $factorysemestre->getByFormation($id_formation) as $sem){
	    $etu_sem = $factorysemestre_etudiant->get($id,$sem->id_semestre);
	    $semestrearray[$sem->id_semestre]['id'] = $sem->id_semestre;
            $semestrearray[$sem->id_semestre]['label'] = "Semestre ".$sem->num_semestre;
	    if($etu_sem != null)
		$semestrearray[$sem->id_semestre]['checked'] = true;
	    else
		$semestrearray[$sem->id_semestre]['checked'] = false;
		
	    foreach( jDao::get('formations~ue_semestre_ue')->getBySemestre($sem->id_semestre) as $ue){
		if($ue->optionelle == '1'){
		    $libelle = ($ue->libelle != '') ? ' : ' .$ue->libelle : '';
		    $ueoption[$ue->id_ue]['id'] = $ue->id_ue;
		    $ueoption[$ue->id_ue]['label'] = $ue->code_ue . $libelle;
		    //Check si l'etudiant fais deja partie de cette option
		    $ueoption[$ue->id_ue]['sem'] = $sem->id_semestre;
		    if(strstr($etu_sem->options,$ue->code_ue) != FALSE){
			$ueoption[$ue->id_ue]['checked'] = true;
		    }
		    else{
			$ueoption[$ue->id_ue]['checked'] = false;
		    }
		}
	    }
        }
       
        $tpl = new jTpl();
        $tpl->assign('submitAction', 'etudiants~etudiants:save_semestre_ue');
        $tpl->assign('id', $id);
        $tpl->assign('id_formation', $this->param('id_formation', 0));
	$tpl->assign('semestres',$semestrearray);
	$tpl->assign('ueoption',$ueoption);
          
        $rep->setTitle('Définition des Semestres et UEs optionelles');
        
        $rep->body->assign('MAIN', $tpl->fetch('etudiantedit_semestre'));
        return $rep;

    }
    
    public function save_semestre_ue(){
	$id = $this->param('id', 0);
	$factoryetudiant_semestre = jDao::get('etudiants~etudiants_semestre');
	$factoryue_semestre_ue = jDao::get('formations~ue_semestre_ue');
	$option ="";
	//TODO Verifier si suppression necessaire risque de perte d'information
	//$factoryetudiant_semestre->deleteByEtudiant($id);


 
//    Rajouts de l'etudiant au semestre(PAS FAIS a voir) et de ses option pour le semestre
    
        foreach($this->param('semestres', array()) as $semestre){	    
	    foreach($this->param('ues', array()) as $ue){
		$uerecord = $factoryue_semestre_ue->get($ue,$semestre);
		if($uerecord != null){
		    $option = $option . $uerecord->code_ue . ",";
		}
	    }
		$factoryetudiant_semestre->updateOption($id,$semestre,$option);
		$option ="";
            
        }
	
        jMessage::add('UEs optionelles définies !', 'confirm');
        
        $rep = $this->getResponse('redirect');
        $rep->action = 'etudiants~etudiants:view';
        $rep->params = array('id' => $this->param('id', 0));
        return $rep;
    }
	
	 /**
     * Après l'insertion de l'etudiant, on l'ajoute dans tous les semestres de sa formation
     */    
    protected function _afterCreate($form, $id, $resp){
	
	$semestre = jDao::get('formations~semestre');
	$factory = jDao::get('etudiants~etudiants_semestre');
	// On recupere les formations selectionné
	foreach ($form->getData('formations') as $row1) {
	// On recupere les semestres de la formation
	    $list_semestre = $semestre->getByFormation($row1);
			
	    foreach ($list_semestre as $row) {
		//On associe l'etudiant au semestres de ses formations
		$etudiant_semestre1 = jDao::createRecord('etudiants~etudiants_semestre');
		$etudiant_semestre1->num_etudiant = $id;
		$etudiant_semestre1->id_semestre = $row->id_semestre;
		$etudiant_semestre1->statut = 'NOK';
		$factory->insert($etudiant_semestre1);
	    }
	}
    }
    
    protected function _afterUpdate($form, $id, $resp){
	
	$semestre = jDao::get('formations~semestre');
	$factory = jDao::get('etudiants~etudiants_semestre');
	
	//TODO A modifier si le status du semestre est different de "NOK" risque de perte d'info
	$factory->deleteByEtudiant($id);
	
	// On recupere les formations selectionné
	foreach ($form->getData('formations') as $row1) {
	// On recupere les semestres de la formation
	    $list_semestre = $semestre->getByFormation($row1);
			
	    foreach ($list_semestre as $row) {
		//On associe l'etudiant au semestres de ses formations
		$etudiant_semestre1 = jDao::createRecord('etudiants~etudiants_semestre');
		$etudiant_semestre1->num_etudiant = $id;
		$etudiant_semestre1->id_semestre = $row->id_semestre;
		$etudiant_semestre1->statut = 'NOK';
		$factory->insert($etudiant_semestre1);
		jMessage::add("Modification  reussie !");
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
	$semestresarray = array();
	$optionsarray = array();
	
	$liste_semestre = $factoryetudiant_semestre->getByEtudiantNum($this->param('id',0));
	
	foreach($liste_semestre as $semestre){
	    $formation = $factoryformation->get($semestre->id_formation);
	    $formationarray[$formation->id_formation] = $formation;
	    $semestresarray[$semestre->id_semestre] = $semestre;
	}
	
	$form->deactivate('formations');
	$tpl->assign('formations', $formationarray);
	$tpl->assign('semestres', $semestresarray);
    }
   
    public function _create($form, $resp, $tpl){
        $resp->setTitle('Créer un nouvel etudiant');
	$form->deactivate('formations',FALSE);
    }
   
    public function _editUpdate($form, $resp, $tpl){
	$resp->setTitle('Modifier un etudiant');
	$form->deactivate('formations',FALSE);
       
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
        jDao::get('etudiants~etudiants_semestre')->deleteByEtudiant($id);
	jDao::get('ue~note')->deleteByEtudiant($id);
        return true;
    }
    
    public function recherche() {
       
        $form = jForms::fill('etudiants~recherche');
	
	$num_etudiant = $form->getData('num_etudiant');
	
	$rep = $this->getResponse('redirect');
    	$rep->action = 'etudiants~etudiants:view';
	$rep->params = array('id'=>$num_etudiant);
	
	return $rep;
    }
}