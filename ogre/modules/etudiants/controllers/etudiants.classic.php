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
        
        //$form->getControl('nom_du_control')->datasource->data = $data;
        
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
                    $ueoption[$ue->id_ue.$sem->id_semestre]['id'] = $ue->id_ue;
                    $ueoption[$ue->id_ue.$sem->id_semestre]['label'] = $ue->code_ue . $libelle;
                    //Check si l'etudiant fais deja partie de cette option
                    $ueoption[$ue->id_ue.$sem->id_semestre]['sem'] = $sem->id_semestre;
                    if(strstr($etu_sem->options,$ue->code_ue) != FALSE){
                        $ueoption[$ue->id_ue.$sem->id_semestre]['checked'] = true;
                    }
                    else{
                        $ueoption[$ue->id_ue.$sem->id_semestre]['checked'] = false;
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
		$ue_sem=explode(":",$ue);
		$uerecord = $factoryue_semestre_ue->get($ue_sem[0],$ue_sem[1]);
		if($uerecord != null && $semestre==$ue_sem[1] ){
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
    
    public function _beforeSaveUpdate($form, $form_daorec, $id){
	$liste = $form->getModifiedControls();
	jLog::dump($liste);
    }
    
    public function _preUpdate($form){
	$form->initModifiedControlsList();
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
		$etudiant_semestre1->statut = 'ENC';
		$factory->insert($etudiant_semestre1);
		jMessage::add("Modification  reussie !");
	    }
	}
    }
   
    public function _index($resp, $tpl){
        $resp->setTitle('Liste des etudiants');
        
        $order = $this->param('order');
        $dir = $this->param('dir');
        
        $nom = $this->param('nom');
        
        $params = array();
        
        if($order != null) {
            //dir : ASC or DESC
            if(($dir != null)) {
                if($dir == 'desc')
                    $params['dir'] = 'desc';
                else
                   $params['dir'] = 'asc';
            }
            
            //order on what
            if($order == 'num' || $order == 'numero')
                $params['order'] = 'num';
            else if($order == 'nom')
                $params['order'] = 'nom';
        }
        
        if($nom != ''){
            $params['nom'] = $nom;
        }
        
        $tpl->assign('params', $params);
    }
    
    
    protected function _indexSetConditions($cond){
        $order = $this->param('order');
        $dir = $this->param('dir');
        
        if(($dir != null)) {
            if($dir == 'desc')
                $dir = 'desc';
            else
                $dir = 'asc';
        }
        
        if($order != null) {
            if($order == 'num' || $order == 'numero')
                $cond->addItemOrder('num_etudiant', $dir);
            else if($order == 'nom')
                $cond->addItemOrder('nom', $dir);
        }
        //TODO faire interface qui va avec ce tri
        
        
        
        $nom = $this->param('nom');
        if($nom != ''){
            $params['nom'] = $nom;
            $cond->addCondition('nom','=', $nom);
        }
        
    }
   
    public function _view($form, $resp, $tpl){
        $basepath = $GLOBALS['gJConfig']->urlengine['basePath'];
        
        $resp->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.core.min.js');
        $resp->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.widget.min.js');
        $resp->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.button.min.js');
        
        //$resp->addCSSLink($basepath.'jelix/jquery/themes/smoothness/jquery.ui.all.css');
   
        
        
        $resp->setTitle('Détails de l\'etudiant');
        $form->deactivate('formations');
        /*
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
        
        $tpl->assign('formations', $formationarray);
        $tpl->assign('semestres', $semestresarray);
        */
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
        $rep = $this->getResponse('redirect');
        
        $form = jForms::fill('etudiants~recherche');

        $num_etudiant = $form->getData('num_etudiant');
        $nom = $form->getData('nom');
        
        $rep->action = 'ogre~default:index';
        
        if($num_etudiant && $num_etudiant != ''){
            if(jDao::get('etudiants~etudiants')->get($num_etudiant)) { 
                $rep->action = 'etudiants~etudiants:view';
                $rep->params = array('id'=>$num_etudiant);
            }
            else{
                jMessage::add("L'etudiant numéro ".$num_etudiant." n'existe pas", 'error');
            }
        }
        else if($nom && $nom != ''){
            if( ($etudiants = jDao::get('etudiants~etudiants')->getByNom($nom)) != false){
                
                $i=0;
                foreach($etudiants as $e){
                    $num_etudiant = $e->num_etudiant;
                    $i++;
                }
                if($i == 1){
                    $rep->action = 'etudiants~etudiants:view';
                    $rep->params = array('id'=>$num_etudiant);
                }
                else{
                    $rep->action = 'etudiants~etudiants:index';
                    $rep->params = array('nom'=>$nom);
                }
            }
            else{
                jMessage::add("L'etudiant s'apellant ".$nom." n'existe pas", 'error');
            }
        }
        else{
            jMessage::add("Il faut rentrer soit le numéro étudiant soit le nom pour effectuer la recherche !", 'error');
        }
        
        
        return $rep;
    }
}