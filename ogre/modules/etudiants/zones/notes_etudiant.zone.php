<?php
/**
* @package   ogre
* @subpackage etudiants
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class notes_etudiantZone extends jZone {
    protected $_tplname='notes_etudiant';

    protected function _prepareTpl(){
        //$this->_tpl->assign('foo','bar');
        
        jClasses::inc('utils~customSQL');
        jClasses::inc('utils~Moyenne');
        
        
        $num_etu = $this->param('num_etudiant');
        $notes_regular = customSQL::findRegularNoteByEtudiant($num_etu);
        
        $options = array();
        foreach( jDao::get('etudiants~etudiants_semestre')->getByEtudiant($num_etu) as $etu_sem){
            if($etu_sem->options != '')
                $options[$etu_sem->id_semestre] = explode(',', $etu_sem->options);
        }
        
        $moyennes = $array_notes = array();
        $libelle = array();
        $dispense = array();
        foreach($notes_regular as $note){
            
            //Si c'est une option et que l'etudiant ne la suit pas, on passe a la suivante
            if($note->is_option == 1 && ((isset($options[$note->id_semestre]) && !in_array($note->code_ue, $options[$note->id_semestre])) || empty($options[$note->id_semestre])))
                continue;
            
            
            if(is_numeric($note->flag_dispense))
                $note->valeur = -2 ;
                
            if(isset($dispense[$note->id_ue]) || customSQL::DispenseExiste($note->id_semestre, $note->num_etudiant, $note->id_ue)){
                $note->valeur = -2 ;
                $dispense[$note->id_ue] = 1;
            }
            
            $array_notes[$note->id_semestre][$note->id_ue][] = $note;
            
            $libelle['semestre'][$note->id_semestre] = $note->num_semestre;
            $libelle['formation'][$note->id_semestre] = $note->code_formation.' ('.$note->annee.')' ;
            $libelle['ue'][$note->id_semestre][$note->id_ue] = ($note->ue_libelle != '') ? $note->code_ue .' - ' .$note->ue_libelle : $note->code_ue;
            
            if(isset($dispense[$note->id_ue]))
                $libelle['ue'][$note->id_semestre][$note->id_ue] .= " (DISP)";
                
                
            if(!isset($moyennes[$note->id_semestre][$note->id_ue])){
                $moyennes[$note->id_semestre][$note->id_ue] = Moyenne::calcMoyenne($note->id_semestre, $num_etu, $note->id_ue);
            }
                
        }

        $this->_tpl->assign('submitAction', 'etudiants~saisie_note:save_saisie');
        $this->_tpl->assign('num_etudiant', $this->param('num_etudiant') );
        $this->_tpl->assign('notes', $array_notes);
        $this->_tpl->assign('moyennes', $moyennes);
        $this->_tpl->assign('libelle', $libelle);
    }
}
