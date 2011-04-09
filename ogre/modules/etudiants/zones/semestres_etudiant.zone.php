<?php
/**
* @package   ogre
* @subpackage etudiants
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class semestres_etudiantZone extends jZone {
    protected $_tplname='semestres_etudiant';

    protected function _prepareTpl(){

        $factoryformation = jDao::get('formations~formation');
        $formationarray = array();
        
        $liste_semestre = jDao::get('etudiants~etudiants_semestre_semestre')->getByEtudiantNum($this->param('num_etudiant',0));
        
        foreach($liste_semestre as $semestre){
            $formation = $factoryformation->get($semestre->id_formation);
            $formationarray[$formation->id_formation] = $formation;
        }
        
        
        
        // TODO : Sauvegarde des semestres ne marche plus
        
        $this->_tpl->assign('formations', $formationarray);
        $this->_tpl->assign('num_etudiant', $this->param('num_etudiant'));
    }
}
