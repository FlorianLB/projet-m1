<?php


class dispense_predefZone extends jZone {
    protected $_tplname='dispense_predef';

    protected function _prepareTpl(){
        //$this->_tpl->assign('foo','bar');
        
        jClasses::inc('utils~customSQL');
        
        $results = customSQL::findDispensePredefByEtudiant($this->param('num_etudiant'));
        
        $tmp = array();
        $libelle = array();
        foreach($results as $row){
            $tmp[$row->id_semestre][] = $row;
            
            $libelle['semestre'][$row->id_semestre] = $row->num_semestre;
            $libelle['formation'][$row->id_semestre] = $row->code_formation.' ('.$row->annee.')' ;
            $libelle['ue'][$row->id_ue] = ($row->ue_libelle != '') ? $row->code_ue .' - ' .$row->ue_libelle : $row->code_ue;
        }
        
        $this->_tpl->assign('submitAction', 'etudiants~dispense:save_disp_predef');
        $this->_tpl->assign('num_etudiant', $this->param('num_etudiant') );
        $this->_tpl->assign('dispenses', $tmp);
        $this->_tpl->assign('libelle', $libelle);
        
    }
}