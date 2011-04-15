<?php
/**
* @package   ogre
* @subpackage etudiants
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class dispense_persoZone extends jZone {
    protected $_tplname='dispense_perso';

    protected function _prepareTpl(){
        //$this->_tpl->assign('foo','bar');
        
        jClasses::inc('utils~customSQL');
        
        $results = customSQL::findDispenseByEtudiant($this->param('num_etudiant'));
        
        $tmp = array();
        $libelle = array();
        foreach($results as $row){
            $tmp[$row->id_semestre][$row->id_ue][] = $row;
            
            $libelle['semestre'][$row->id_semestre] = $row->num_semestre;
            $libelle['formation'][$row->id_semestre] = $row->code_formation.' ('.$row->annee.')' ;
            $libelle['ue'][$row->id_ue] = ($row->ue_libelle != '') ? $row->code_ue .' - ' .$row->ue_libelle : $row->code_ue;
        }
        
        $this->_tpl->assign('submitAction', 'etudiants~dispense:save_disp_perso');
        $this->_tpl->assign('num_etudiant', $this->param('num_etudiant') );
        $this->_tpl->assign('dispenses', $tmp);
        $this->_tpl->assign('libelle', $libelle);
        
    }
}
