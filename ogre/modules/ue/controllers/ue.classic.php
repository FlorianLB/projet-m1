<?php
jClasses::inc('utils~customSQL');

class ueCtrl extends jControllerDaoCrud {
 
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
     * Après l'insertion de l'ue, on passe toute les ue avec le meme code en tant que non-active
     * On crée les epreuves avec leur coef en fonction de la formule
     */
    protected function _afterCreate($form, $id, $resp){
        //TODO Modifier pour OldUeVersion
        //jDao::get('ue~ue')->setOldUeVersion($id, $form->getData('code_ue'));
        
        //exctraction de la formule
        jClasses::inc('utils~Formule');
        $formules = array();
        $formules[0] = Formule::parseFormuleUe($form->getData('formule'));
        $formules[1] = Formule::parseFormuleUe($form->getData('formule2'));
        $formules[2] = Formule::parseFormuleUe($form->getData('formule_salarie'));
        //initialisation des dao
        $factory = jDao::get('ue~epreuve');
        $epreuve = jDao::createRecord('ue~epreuve');
       
        // pour chaques formules on cree les epreuves

        foreach($formules as $formule){
         //pour chaqun des element de la formule on crée une epreuve
            foreach($formule[2] as $epreuve_temp){
            //if(strtolower($var[$j][2][$i]) != "sup"){
                if(!customSQL::epreuveExisteDeja($id,$epreuve_temp)){
                    $epreuve = jDao::createRecord('ue~epreuve');
                    $epreuve->id_ue = $id;
                    $epreuve->coeff = 1;
                    $epreuve->type_epreuve = $epreuve_temp;
                    $factory = jDao::get('ue~epreuve');
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
        $resp->setTitle('Créer une nouvelle UE');
    }
    
    protected function _editUpdate($form, $resp, $tpl){
        $resp->setTitle('Modifier une UE');
        
        $form->setReadOnly('formule');
        $form->setReadOnly('formule2');
        $form->setReadOnly('formule_salarie');
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
