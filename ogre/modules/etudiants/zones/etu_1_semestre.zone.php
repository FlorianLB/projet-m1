<?php
/**
* @package   ogre
* @subpackage etudiants
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class etu_1_semestreZone extends jZone {
    protected $_tplname='etu_1_semestre';

    protected function _prepareTpl(){
        
        $id = $this->param('num_etudiant', 0);
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
                    
                    if( $etu_sem != null && (strstr($etu_sem->options,$ue->code_ue) != FALSE)){
                        $ueoption[$ue->id_ue.$sem->id_semestre]['checked'] = true;
                    }
                    else{
                        $ueoption[$ue->id_ue.$sem->id_semestre]['checked'] = false;
                    }
                }
            }
        }
       
        $this->_tpl->assign('submitAction', 'etudiants~etudiants:save_semestre_ue');
        $this->_tpl->assign('id', $id);
        $this->_tpl->assign('id_formation', $id_formation);
        $this->_tpl->assign('semestres',$semestrearray);
        $this->_tpl->assign('ueoption',$ueoption);
        
        
        
        
    }
}
