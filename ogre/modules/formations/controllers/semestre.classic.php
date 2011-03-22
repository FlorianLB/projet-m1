<?php

class semestreCtrl extends jController{
    
    
    public function view(){
        $rep = $this->getResponse('html');
        
        $id = $this->param('id', 0);
        
        $semestre = jDao::get('formations~semestre')->get($id);
        
        
        $formation = jDao::get('formations~formation')->get($semestre->id_formation);
        //Le nom est le libelle de la formation ou a défaut son code
        $formationName = $formation->libelle ? $formation->libelle : $formation->code_formation;
        
        
        $form = jForms::create('formations~semestre_ue', $id);
        $form->initControlFromDao('ues', 'formations~semestre_ue', null, array('id_semestre', 'id_ue'));
        
        $tpl = new jTpl();
        $tpl->assign('semestre', $semestre);
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'formations~semestre:save_ues');
        $tpl->assign('id', $id);
        $tpl->assign('id_formation', $this->param('id_formation', ''));
        
        $rep->body->assign('MAIN', $tpl->fetch('semestre_view'));
        
        $rep->setTitle('Semestre '.$semestre->num_semestre.' de '.$formationName);
        
        return $rep;
    }
    
    public function save_ues(){
        $id = $this->param('id', 0);
        
        $form = jForms::fill('formations~semestre_ue', $id);
        
        $form->saveControlToDao('ues', 'formations~semestre_ue', null, array('id_semestre', 'id_ue'));
        
        jMessage::add('Changements validés', 'confirm');
        
        $rep = $this->getResponse('redirect');
        $rep->action = 'formations~semestre:view';
        $rep->params = array('id' => $id);
        return $rep;
    }
    
}