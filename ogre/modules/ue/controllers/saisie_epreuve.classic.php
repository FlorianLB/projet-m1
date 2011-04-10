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

    private static $VALUE_ABS = -1;


    function intro() {
        $rep = $this->getResponse('html');
        
        $basepath = $GLOBALS['gJConfig']->urlengine['basePath'];
        
        $rep->addJSLink( $basepath.'js/saisie.js');
        $rep->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.core.min.js');
        $rep->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.widget.min.js');
        $rep->addJSLink($basepath.'jelix/jquery/ui/jquery.ui.button.min.js');
        
        //$rep->addCSSLink($basepath.'jelix/jquery/themes/smoothness/jquery.ui.all.css');


        $form = jForms::create('ue~intro_saisie_epreuve');

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'ue~saisie_epreuve:saisie');

        $rep->setTitle('Saisie de notes - Choix');

        $rep->body->assign('MAIN', $tpl->fetch('ue~intro_saisie_epreuve'));
        return $rep;
    
    
    
    }
    /*
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
        $tpl->assign('id_semestre', $semestre->id_semestre);
        $tpl->assign('submitAction', 'ue~saisie_epreuve:saisie');
        
        $rep->setTitle('Saisie de notes pour '.$formation->code_formation." ".$formation->annee.' - étape 2/2');
        
        $rep->body->assign('MAIN', $tpl->fetch('ue~intro2_saisie_epreuve'));
        return $rep;
    }
    */
    
    
    
    function saisie(){
        $rep = $this->getResponse('html');
        
        $formation = jDao::get('formations~formation')->getByCodeAnnee($this->param('formation'), $this->param('annee'));
        $semestre = jDao::get('formations~semestre')->getByFormationNum($formation->id_formation, $this->param('semestre'));
        $id_semestre = $semestre->id_semestre;
        $id_epreuve = $this->param('epreuve');
        
        $data = array();
        
        
        jClasses::inc('utils~customSQL');

        foreach( customSQL::findEtudiantsNoteByEpreuveSemestre($id_epreuve, $id_semestre) as $item ) {
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
        $tpl->assign('id_epreuve', $id_epreuve);
        $tpl->assign('id_semestre', $id_semestre);
        
        
        $rep->setTitle('Saisie de notes pour ');
        
        $rep->body->assign('MAIN', $tpl->fetch('ue~saisie_epreuve'));
        return $rep;
    }
    
    function save_saisie(){
        $rep = $this->getResponse('redirect');
        
        $id_epreuve = $this->param('id_epreuve');
        $id_semestre = $this->param('id_semestre');
        $notes = $this->param('note');
        
        $factory = jDao::get('ue~note');
        foreach($notes as $num_etu => $note){
            if( $note == ''){
                $factory->delete($id_epreuve, $num_etu, $id_semestre);
               continue;
            }

            if( strcasecmp($note, 'ABS') == 0 ) {
                $valeur = self::$VALUE_ABS;
            }
            else if( floatval($note) >= 0 && floatval($note) <= 20) {
                $valeur = $note;
            }
            else
                continue;
            
            $old_note = $factory->get($id_epreuve, $num_etu, $id_semestre);
            
            // Si il n'y a pas encore de note
            if( !$old_note) {
                $record = jDao::createRecord('ue~note');
                $record->id_epreuve =  $id_epreuve;
                $record->id_semestre = $id_semestre;
                $record->num_etudiant = $num_etu;
                $record->valeur = $valeur;
                $factory->insert($record);
            }
            //MAJ de la note
            else{
                $old_note->valeur = $valeur;
                $factory->update($old_note);
            }
        }
        
        jMessage::add('Notes enregistrées !', 'confirm');
        
        $rep->action = 'ue~saisie_epreuve:saisie';
        $rep->params = array('id_epreuve' =>$id_epreuve, 'id_semestre' => $id_semestre, );
        return $rep;
    }
    
    
}
