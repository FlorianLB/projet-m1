<?php
jClasses::inc('utils~customSQL');

class ueCtrl extends jControllerDaoCrud {
 
    public $pluginParams = array(
        'delete' => array( 'jacl2.right' =>'ue.delete.ue'),
        'editupdate' => array('jacl2.right' =>'ue.modify.ue'),
        'create' => array('jacl2.right' =>'ue.create.ue')
    );
 
    protected $dao = 'ue~ue';
 
    protected $form = 'ue~ueform';
    
    protected $listTemplate = 'ue~ue_list';
    protected $editTemplate = 'ue~ue_edit';
    protected $viewTemplate = 'ue~ue_view';
    
    protected $propertiesForList = array('code_ue', 'libelle', 'coeff','formule');
 
 
    /**
     * 
     */
    protected function _beforeSaveCreate($form, $form_daorec){
        $form_daorec->credits = $form_daorec->coeff;
    }
    
    protected function _beforeSaveUpdate($form, $form_daorec, $id){
        $form_daorec->credits = $form_daorec->coeff;
    }
     
     
     
    /**
     * On crée les epreuves avec leur coef en fonction de la formule
     */
    protected function _afterCreate($form, $id, $resp){
        
        //exctraction de la formule
        jClasses::inc('utils~Formule');
        $formules = array();
        $formules[0] = Formule::parseFormuleUe($form->getData('formule'));
        $formules[1] = Formule::parseFormuleUe($form->getData('formule2'));
        $formules[2] = Formule::parseFormuleUe($form->getData('formule_salarie'));
        $formules[3] = Formule::parseFormuleUe($form->getData('formule_endette'));
        //initialisation des dao
        $factory = jDao::get('ue~epreuve');
        // pour chaques formules on cree les epreuves

        foreach($formules as $i => $formule){
         //pour chaqun des element de la formule on crée une epreuve
            foreach($formule[2] as $epreuve_temp){
            //if(strtolower($var[$j][2][$i]) != "sup"){
                if(!customSQL::epreuveExisteDeja($id,$epreuve_temp)){
                    $epreuve = jDao::createRecord('ue~epreuve');
                    $epreuve->id_ue = $id;
                    $epreuve->coeff = 1;
                    $epreuve->type_epreuve = $epreuve_temp;
	
	if($i == 1)
	    $epreuve->rattrapage = true;
	    
                    $factory->insert($epreuve);
                }
            }
        }
        
	jMessage::add("La creation a reussie !");
    }
 
 
    protected function _index($resp, $tpl){
	
        $resp->setTitle('Liste des UEs');
         

    }
    
    protected function _view($form, $resp, $tpl){
        
        $ue = $form->getData('code_ue');
        
        $resp->setTitle('Détails de '.$ue);
        
    }
    
    protected function _create($form, $resp, $tpl){
        
        /* Autocomplete sur les formules */
        
        $basepath = $GLOBALS['gJConfig']->urlengine['basePath'];
        
        $resp->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.core.min.js');
        $resp->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.widget.min.js');
        $resp->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.position.min.js');
        $resp->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.autocomplete.min.js');
        
        $resp->addCSSLink($basepath.'jelix/jquery/themes/smoothness/jquery.ui.all.css');
        
        $final = array();
        jClasses::inc('utils~customSQL'); 
        jClasses::inc('jediSettings~jediSettings');
        
        //Formule
        $tmp = array();
        foreach(customSQL::getFormuleFrequente('formule', jediSettings::get( 'nb.formules.creation.ue', 5)) as $f){
            if($f->formule != null)
                $tmp[] = $f->formule;
        }
        $final[] = array('id' => 'jforms_ue_ueform_formule', 'data' => json_encode($tmp));
        
        //Formule rattrapage
        $tmp = array();
        foreach(customSQL::getFormuleFrequente('formule2') as $f){
            if($f->formule2 != null)
                $tmp[] = $f->formule2;
        }
        $final[] = array('id' => 'jforms_ue_ueform_formule2', 'data' => json_encode($tmp));
        
        //Formule salarié
        $tmp = array();
        foreach(customSQL::getFormuleFrequente('formule_salarie') as $f){
            if($f->formule_salarie != null)
                $tmp[] = $f->formule_salarie;
        }
        $final[] = array('id' => 'jforms_ue_ueform_formule_salarie', 'data' => json_encode($tmp));
        
        //Formule endetté
        $tmp = array();
        foreach(customSQL::getFormuleFrequente('formule_endette') as $f){
            if($f->formule_endette != null)
            $tmp[] = $f->formule_endette;
        }
        $final[] = array('id' => 'jforms_ue_ueform_formule_endette', 'data' => json_encode($tmp));
        

        $tpl->assign('formules', $final);
        $resp->setTitle('Créer une nouvelle UE');
    }
    
    protected function _editUpdate($form, $resp, $tpl){

        $resp->setTitle('Modifier une UE');
        
        $form->setReadOnly('formule');
        $form->setReadOnly('formule2');
        $form->setReadOnly('formule_salarie');
        $form->setReadOnly('formule_endette');
    }
    
    protected function _delete($id, $resp) {
        
        $factorynote = jDao::get('ue~note');
        $factoryepreuve = jDao::get('ue~epreuve');
        $factorysemestre_ue = jDao::get('formations~semestre_ue');
        
        $liste_epreuve = $factoryepreuve->getByUe($id);
        foreach($liste_epreuve as $epreuve){
            $factorynote->deleteByEpreuve($epreuve->id_epreuve);
        }
        
        $factoryepreuve->deleteByUe($id);
        $factorysemestre_ue->deleteByUe($id);
        jMessage::add("La suppression a reussie !");

        return true;
    }
 
}
