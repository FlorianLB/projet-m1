<?php


class dispenseCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $num_etudiant = $this->param('num_etudiant');
        
        
        
        $tpl = new jTpl();
        $tpl->assign('num_etudiant', $num_etudiant);
        
        $rep->body->assign('MAIN', $tpl->fetch('dispense'));
        
        $rep->setTitle('Dispenses de '.$num_etudiant);

        return $rep;
    }
    
    
    
    function save_disp_perso() {
        $rep = $this->getResponse('redirect');

        $num_etu = $this->param('num_etudiant');
        $disp = $this->param('disp');
        
        $factory = jDao::get('etudiants~dispense_perso');
        
        $factory->delByEtudiant($num_etu);
        
        foreach($disp as $string){

            $array = explode(':', $string);
            $id_epreuve = $array[0];
            $id_semestre = $array[1];
            
            $old = $factory->get($id_epreuve, $num_etu, $id_semestre);
            
            // Si il n'y a pas encore de dispense
            if( !$old) {
                $record = jDao::createRecord('etudiants~dispense_perso');
                $record->id_epreuve =  $id_epreuve;
                $record->id_semestre = $id_semestre;
                $record->num_etudiant = $num_etu;
                $factory->insert($record);
            }
        }
        
        jMessage::add('Dispenses personnalisées enregistrées !', 'confirm');
        
        $rep->action = 'etudiants~dispense:index';
        $rep->params = array('num_etudiant' =>$num_etu);
        
        return $rep;
    }
    
    
}
