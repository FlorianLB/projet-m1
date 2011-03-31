<?php
/**
* @package   ogre
* @subpackage formations
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class saisie_epreuveCtrl extends jController {


    function intro() {
        $rep = $this->getResponse('html');

        $form = jForms::create('ue~intro_saisie_epreuve');

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'ue~saisie_epreuve:intro2');

        $rep->setTitle('Saisie de notes - Choix de formation - étape 1/2');

        $rep->body->assign('MAIN', $tpl->fetch('ue~intro_saisie_epreuve'));
        return $rep;
    }
    
    function intro2() {
        $rep = $this->getResponse('html');

        $form = jForms::fill('ue~intro_saisie_epreuve');
        
        $formation = jDao::get('formations~formation')->getByCodeAnnee($form->getData('formation'), $form->getData('annee'));
        $semestre = jDao::get('formations~semestre')->getByFormationNum($formation->id_formation, $form->getData('semestre'));
        
        jForms::destroy('ue~intro_saisie_epreuve');
        
        $form = jForms::create('ue~intro2_saisie_epreuve');

        //Remplissage de la menulist UE
        $data = array( 0 => "Choisissez l'UE");
        foreach(jDao::get('formations~ue_semestre_ue')->getBySemestre($semestre->id_semestre) as $ue)
            $data[$ue->id_ue] = $ue->code_ue;

        $form->getControl('ue')->datasource->data = $data;

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('id_formation', $formation->id_formation);
        $tpl->assign('submitAction', 'ue~saisie_epreuve:saisie');
        
        $rep->setTitle('Saisie de notes pour '.$formation->code_formation." ".$formation->annee.' - étape 2/2');
        
        $rep->body->assign('MAIN', $tpl->fetch('ue~intro2_saisie_epreuve'));
        return $rep;
    }
    
    
    
    
    function saisie(){
        $rep = $this->getResponse('html');
        
        $id_formation = $this->param('id_formation');
        $form = jForms::fill('ue~intro2_saisie_epreuve');
        
        $id_epreuve = $form->getData('epreuve');
        
        $data = array();
        
        // TODO : la requete actuelle ne chope que les etudiant ayant une note, il faut en faire une qui choppe tout ceux inscrit dans l'UE
        foreach( jDao::get('ue~etudiant_note')->getByEpreuve($id_epreuve) as $item ) {
            $d = array();
            $d['etudiant']['num'] = $item->num_etudiant;
            $d['etudiant']['nom'] = ($item->nom_usuel != '') ? $item->nom_usuel : $item->nom;
            $d['etudiant']['prenom'] = $item->prenom;
            $d['valeur'] = $item->valeur;
            $data[] = $d;
        }

        
        $tpl = new jTpl();
        $tpl->assign('submitAction', 'ue~saisie_epreuve:save_saisie');
        $tpl->assign('data', $data);
        
        $rep->setTitle('Saisie de notes pour '.$id_formation);
        
        $rep->body->assign('MAIN', $tpl->fetch('ue~saisie_epreuve'));
        return $rep;
    }
    
    function save_saisie(){
        
    }
    
    
}
