<?php
/**
* @package   ogre
* @subpackage phpexcel
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/
require_once (LIB_PATH.'phpexcel\PHPExcel.php');
class defaultCtrl extends jController {
    /**
    *
    */
    
    function index() {
        $rep = $this->getResponse('html');
        $content = '<p>Ok nikel, tout marche</p>
            <p>Liens temporaires : </p>
            <ul>
                    <li><a href="'.jUrl::get('phpexcel~exportetudiants').'">Exporter Etudiants</a></li>
            </ul>
        ';
        
        $rep->body->assign('MAIN', $content);


        return $rep;
    }

    function exportetudiants() {
        $rep = $this->getResponse();
        $counter = 1;
        $listeunetudiant = "";
        $name = $this->param('name');
        $dbw = jDb::getDbWidget(); // instead of getConnection()
         //$record = $dbw->fetchFirst("SELECT name, first_name FROM user");
        $liste = $dbw->fetchAll("SELECT * FROM  `etudiants` ");
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                                                 ->setLastModifiedBy("Maarten Balliauw")
                                                                 ->setTitle("Office 2007 XLSX Test Document")
                                                                 ->setSubject("Office 2007 XLSX Test Document")
                                                                 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                                                 ->setKeywords("office 2007 openxml php")
                                                                 ->setCategory("Test result file");
        
        foreach ($liste as $unStdClass) {
            $unetudiant = get_object_vars($unStdClass);
            //print_r($unetudiant);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$counter, $unetudiant["nom"])
                                                ->setCellValue('B'.$counter, $unetudiant["prenom"])
                                                ->setCellValue('C'.$counter, $unetudiant["num_etudiant"])
                                                ->setCellValue('D'.$counter, $unetudiant["date_naissance"]);
            
            $counter++;
        }
        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Etudiant');
        
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="etudiant.xlsx"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        
        //echo $unetudiant[""];
        /*$content = '<p>Ok nikel, tout marche</p>
            <p>Liens temporaires : </p>
            <ul>
                    <li><a href="'.jUrl::get('formations~formations:index').'">Formations CRUD</a></li>
                    <li><a href="'.jUrl::get('etudiants~etudiants:index').'">Etudiants CRUD</a></li>
					<li><a href="'.jUrl::get('ue~ue:index').'">Ue CRUD</a></li>
            </ul>
        ';*/
        //print_r($liste)
        
        //$rep->body->assign('MAIN', $content);
        
        //$rep->body->assign('MAIN',"OK");
        //return $rep;
    }
}

