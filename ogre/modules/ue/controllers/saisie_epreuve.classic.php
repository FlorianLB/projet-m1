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
        $form->setData('id_semestre', $semestre->id_semestre);

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'ue~saisie_epreuve:intro2');

        $rep->body->assign('MAIN', $tpl->fetch('ue~intro_saisie_epreuve'));
        return $rep;
    }
    
    
    
    
    function saisie(){
        $rep = $this->getResponse('html');
        return $rep;
    }
    
    
}
