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

        $name = $_FILES['excel_pv']['tmp_name'];
        try {
            /** Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = PHPExcel_IOFactory::load($name);
            $Feuille = $objPHPExcel->setActiveSheetIndex(0);
            $listenotes = $objPHPExcel->getNamedRanges();
            //$data = "";
            $factory_notes = jDao::get('ue~note');
            foreach($listenotes as $row3){
                //jLog::dump($row3->getName(),'une notes');
                $nomCeluleNomee = $row3->getName();
                list($h, $id_epreuve, $num_etudiant, $id_semestre) = explode("_", $nomCeluleNomee);
                if($id_epreuve != "PTJURRY"){
                    $record = $factory_notes->get($id_epreuve, $num_etudiant, $id_semestre);
                    //$case = ;
                    $note = $row3->getWorksheet()->getCell($row3->getRange())->getValue();
                    $record->valeur = $note;
                    $factory_notes->update($record);
                }

            
                jLog::Log($h . " " . $id_epreuve . " " . $num_etudiant . " " . $id_semestre . " : " . $note ); 
            
                
            }
            
        } catch(Exception $e) {
            jMessage::add('Erreur lors du chargement du fichier : '.$e->getMessage(),'error' );
            return $rep;
        }
        jMessage::add("L'importation a reussie du PV");
        
        return $rep;
    }
 
    
}

