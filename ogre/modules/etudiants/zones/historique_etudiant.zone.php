<?php
/**
* @package   ogre
* @subpackage etudiants
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class historique_etudiantZone extends jZone {
    protected $_tplname='historique_etudiant';

    protected function _prepareTpl(){
        
        jClasses::inc('utils~customSQL');
        
        $result = customSQL::getAllInscriptionsEtudiant($this->param('num_etudiant'));
        
        $insc = array();
        /*
        foreach($result as $i) {
            $code = $i->code_formation.':'.$i->annee;
            $insc[$code][$i->num_semestre] = $i;
        }
        
        $this->_tpl->assign('inscriptions', $insc);
        */
        
        $this->_tpl->assign('inscriptions', $result);
        
    }
}
