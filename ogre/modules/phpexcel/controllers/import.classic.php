<?php

//require_once '../Classes/PHPExcel/IOFactory.php';
require_once (LIB_PATH.'phpexcel/PHPExcel.php');
class importCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        $rep->setTitle("Import de PV");

        $form = jForms::create('import_PV');

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'phpexcel~import:doimport_pv');

        $rep->body->assign('MAIN', $tpl->fetch('etudiants~import_apogee'));

        return $rep;
    }
    function doimport_pv(){
        $rep = $this->getResponse('redirect');
        $rep->action = 'ogre~index';
        $nbnotemodifierr = 0;
        $nbEleveAdmis = 0;
        $name = $_FILES['excel_pv']['tmp_name'];
        try {
            /** Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = PHPExcel_IOFactory::load($name);
            $Feuille = $objPHPExcel->setActiveSheetIndex(0);
            $listenotes = $objPHPExcel->getNamedRanges();
            //$data = "";
            $factory_notes = jDao::get('ue~note');
            
            $notesS1 = array();
            $notesS2 = array();
            $list_idsemestre = array();
            
            foreach($listenotes as $row3){
                //jLog::dump($row3->getName(),'une notes');
                $nomCeluleNomee = $row3->getName();
                list($h, $id_epreuve, $num_etudiant, $id_semestre) = explode("_", $nomCeluleNomee);
                
                
                switch($id_epreuve){
                    case 'PTJURRY' :
                        //TODO
                        break;
                    case 'MoyGen' :
                        //faire ici la vaalidation du semestre
                        $moyenne = $row3->getWorksheet()->getCell($row3->getRange())->getCalculatedValue();
                        $list_idsemestre[$id_semestre] = 0 ;
                        $temsddsdkeys = array_keys($list_idsemestre);
                        if ($id_semestre == $temsddsdkeys[0]){
                            $notesS1[$num_etudiant] = $moyenne;
                           
                        }else{
                            $notesS2[$num_etudiant] = $moyenne;
                        }
                        //if($moyenne >= 10 ){
                        //    $nbEleveAdmis++;
                        //    $factory_etudiant_semestre = jDao::get('etudiants~etudiants_semestre');
                        //    
                        //    $conditions = jDao::createConditions();
                        //    $conditions->addCondition('num_etudiant', '=', $num_etudiant);
                        //    $conditions->addCondition('id_semestre', '=', $id_semestre);
                        //    
                        //    $unsemestreetudiant = $factory_etudiant_semestre->findBy($conditions);
                        //
                        //    foreach ($unsemestreetudiant as $row) {
                        //        $row->statut = 'ADM';
                        //        $factory_etudiant_semestre->update($row);
                        //    }
                        //}else{
                        //    $factory_etudiant_semestre = jDao::get('etudiants~etudiants_semestre');
                        //    
                        //    $conditions = jDao::createConditions();
                        //    $conditions->addCondition('num_etudiant', '=', $num_etudiant);
                        //    $conditions->addCondition('id_semestre', '=', $id_semestre);
                        //    
                        //    $unsemestreetudiant = $factory_etudiant_semestre->findBy($conditions);
                        //
                        //    foreach ($unsemestreetudiant as $row) {
                        //        $row->statut = 'AJO';
                        //        $factory_etudiant_semestre->update($row);
                        //    }                            
                        //    
                        //}
                        
                        break;
                    
                    default :
                        $record = $factory_notes->get($id_epreuve, $num_etudiant, $id_semestre);
                        //$case = ;
                        $note = $row3->getWorksheet()->getCell($row3->getRange())->getValue();
                        if($note == 'ABS'){$note = -1;}                      
                        if($record->valeur != $note){
                            $record->valeur = $note;
                            $factory_notes->update($record);
                            $nbnotemodifierr++;
                            
                        }
                       
                        break;
                }   
                //if($id_epreuve != "PTJURRY"){
                //
                //    
                //}            
                jLog::dump($list_idsemestre);
            }
            foreach ($notesS1 as $id_etudient => $moyenneue) {
                $list_idsemestre[$id_semestre] = 0 ;
                $temsddsdkeys = array_keys($list_idsemestre);
                if(($moyenneue >= 10) and ($notesS2[$id_etudient] >= 10)){
                    //on valide les deux semestre
                    $this->MarksemestreAs($id_etudient,$temsddsdkeys[0],"ADM");
                    $this->MarksemestreAs($id_etudient,$temsddsdkeys[1],"ADM");
                    $nbEleveAdmis++;
                }elseif(($moyenneue >= 10) and ($notesS2[$id_etudient] < 10)){
                    $this->MarksemestreAs($id_etudient,$temsddsdkeys[0],"ADM");
                    $this->MarksemestreAs($id_etudient,$temsddsdkeys[1],"AJC");                    
                }elseif(($moyenneue < 10) and ($notesS2[$id_etudient] >= 10)){
                    $this->MarksemestreAs($id_etudient,$temsddsdkeys[0],"AJC");
                    $this->MarksemestreAs($id_etudient,$temsddsdkeys[1],"ADM");                  
                }elseif(($moyenneue < 10) and ($notesS2[$id_etudient] < 10)){
                    $this->MarksemestreAs($id_etudient,$temsddsdkeys[0],"AJO");
                    $this->MarksemestreAs($id_etudient,$temsddsdkeys[1],"AJO");                  
                }
                
            }
            
        } catch(Exception $e) {
            jMessage::add('Erreur lors du chargement du fichier : '.$e->getMessage(),'error' );
            return $rep;
        }
        $message = '';
        if ($nbnotemodifierr == 1){
            
            $message = "L'importation du PV a reussie (une note a été modifiée). ";
            
        }else{
            
            $message = "L'importation du PV a reussie ($nbnotemodifierr notes ont été modifiées ). ";
            
        }
        if ($nbEleveAdmis == 1){
            
            $message = $message . "Un étudiant a été admis";
            
        }else{
            
             $message = $message . "$nbEleveAdmis étudiants ont été admis";
            
        }        
        jMessage::add($message);
        return $rep;
    }
    function MarksemestreAs($etudient,$semestre,$valeur ){
        $factory_etudiant_semestre = jDao::get('etudiants~etudiants_semestre');
        
        $conditions = jDao::createConditions();
        $conditions->addCondition('num_etudiant', '=', $etudient);
        $conditions->addCondition('id_semestre', '=', $semestre);
        
        $unsemestreetudiant = $factory_etudiant_semestre->findBy($conditions);
    
        foreach ($unsemestreetudiant as $row) {
            $row->statut = $valeur;
            $factory_etudiant_semestre->update($row);
        }                            
        
        
        
        
    }
 
    
}



