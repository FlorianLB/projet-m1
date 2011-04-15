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
        
        $notes_regular = customSQL::findRegularNoteByEtudiant($this->param('num_etudiant'));
        
        $tmp = array();
        $libelle = array();
        foreach($notes_regular as $note){
            $tmp[$note->id_semestre][$note->id_ue][] = $note;
            
            $libelle['semestre'][$note->id_semestre] = $note->num_semestre;
            $libelle['formation'][$note->id_semestre] = $note->code_formation.' ('.$note->annee.')' ;
            $libelle['ue'][$note->id_ue] = ($note->ue_libelle != '') ? $note->code_ue .' - ' .$note->ue_libelle : $note->code_ue;
        }

        $this->_tpl->assign('submitAction', 'etudiants~saisie_note:save_saisie');
        $this->_tpl->assign('num_etudiant', $this->param('num_etudiant') );
        $this->_tpl->assign('notes', $tmp);
        $this->_tpl->assign('libelle', $libelle);
    }
}
