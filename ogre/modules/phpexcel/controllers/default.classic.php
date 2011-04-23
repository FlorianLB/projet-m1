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
    
    //function index() {
    //    $rep = $this->getResponse('html');
    //    $content = '<p>Ok nikel, tout marche</p>
    //        <p>Liens temporaires : </p>
    //        
    //        <ul>
    //                <li><a href="'.jUrl::get('phpexcel~exportetudiants').'">Exporter Etudiants</a></li>
    //        </ul>
    //    ';
    //    $content .= $this->Num2Letres(1) . "</p>";
    //    $content .= $this->Num2Letres(2) . "</p>";
    //    $content .= $this->Num2Letres(13) . "</p>";
    //    $content .= $this->Num2Letres(16) . "</p>";
    //    $content .= $this->Num2Letres(56) . "</p>";
    //    $rep->body->assign('MAIN', $content);
    //
    //
    //    return $rep;
    //}
    
    function Nums2Case($NumCol,$Numligne){
        return PHPExcel_Cell::stringFromColumnIndex($NumCol-1) . $Numligne;
        //$s= "";
        //for ($i = 6; $i >= 0; $i += -1) {
        //    $x = ((Pow(26, ($i + 1)) - 1) / 25) - 1;
        //    
        //    if ($NumCol > $x){
        //        $s = $s . Chr((($NumCol - $x - 1) / Pow(26, $i)) % 26 + 65);
        //    }
        //}
        //return $s . $Numligne;
    }
    function Num2Colone($NumCol){
        return PHPExcel_Cell::stringFromColumnIndex($NumCol-1);
    }    
    function FillColor($num){
        switch ($num %  5):
            case 0:
                return 'FFFF99CC';
                break;
            case 1:
                return 'FFCC99CC';
                break;
            case 2:
                return 'FF99CC00';
                break;
            case 3:
                return 'FFCC99FF';
                break;
            case 4:
                return 'FFFFCC99';
                break;
            default:
                return 'FFFFFFFF';
                break;
        endswitch;
    }
    
    function exportFormation() {
            
            
        $id = $this->param('id', 0);
        
        $ligne_UE = 2;
        $ligne_epreuve = 3;
        $DebutCounterEtudiant = $ligne_epreuve + 1;

        
        $styleArrayBordureUE = array(
            'borders' => array(
                    'outline' => array(
                            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array('argb' => '00000000'),
                    ),
                     'vertical' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => '00000000'),
                    )
            ),
            
        );
        $styleArrayAlignmentLigneEpreuve = array(
            'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap'	   => true
            )
        );


        //$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);

    
        
        
        
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
        
        //foreach ($liste as $row) {
        //    
        //    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$counter, $row->nom)
        //                                        ->setCellValue('B'.$counter, $row->prenom)
        //                                        ->setCellValue('C'.$counter, $row->num_etudiant)
        //                                        ->setCellValue('D'.$counter, $row->date_naissance);
        //    
        //    $counter++;
        //}
        
        //Ontravail sur une feuille
        $Feuille = $objPHPExcel->setActiveSheetIndex(0);

        //on agrandit la taille de la ligne pour afficher plus d'info
        $Feuille->getRowDimension($ligne_epreuve)->setRowHeight(55);
        //TODO Force cette taille ca si un nom est tros grand sa deborde
        $Feuille->getStyle('3')->applyFromArray($styleArrayAlignmentLigneEpreuve);

        //recupere les deux semestre de la formation
        $Liste_semestres = jDao::get('formations~semestre')->getByFormation($id);
        $tableau_semestre= array();
        foreach ($Liste_semestres as $semestre) {
            $tableau_semestre[] = $semestre->id_semestre;
        }
        
        //recuper liste etudient par semestre
        $factory_etudiant_semestre = jDao::get('etudiants~etudiants_semestre');
        
        $conditions = jDao::createConditions();
        $conditions->addCondition('id_semestre', 'in', $tableau_semestre);
        //il y a des doublee (normalement tous mais on les les ajoute pas apres)
        //TODO
        
        $Liste_etudiant_semestre =  $factory_etudiant_semestre->findBy($conditions);
        
        
         
        $tableau_etudiant_semestre = array();
        $tableau_etudiant = array();
        //asignier num par etudient
        $CounterEtudiant = $DebutCounterEtudiant;
        
        foreach ($Liste_etudiant_semestre as $row) {
            //jLog::dump($row,'Tableau etidient');
            if (array_key_exists($row->num_etudiant,$tableau_etudiant_semestre)){
                //l'etudient existe deja on passe              
            }else{
                $tableau_etudiant_semestre[$row->num_etudiant] = $CounterEtudiant;
            
                //ici on l'a pas toute les info sur les etudients
                //On Mete les info a la fin
                //$Feuille->setCellValueByColumnAndRow(0,$CounterEtudiant, $row->num_etudiant);
                
                $tableau_etudiant[] = $row->num_etudiant;
                $CounterEtudiant ++;
            }

        }    
        //print_r($tableau_etudiant_semestre);
        //jLog::dump($tableau_etudiant,'Tableau etidient');
        
        
        //on  prepar les variables
        $tableau_epreuve = array();
        $CounterEpreuve = 7; //on laise das case pour les etudients num , nom, premom
        
        
               
        
        
        foreach ($tableau_semestre as $unsemestre){
            $conteurFusionTitre = $CounterEpreuve-1;  //oninitialise la variable de calcul de fusion
            //recuperer liste epreuve par semestre
            //recupere la liste des ue qui corespondent au semestre
            $Liste_ue = jDao::get('ue~ue_semestre_ue')->getBySemestre($unsemestre);
            
    
            //$tableau_type_epreuve = array();
            //$tableau_Nom_Ue = array();
            foreach ($Liste_ue as $row) {
                
                //pour chaque ue recuperles les id epreuve qui corespondent
                $Liste_epreuve = jDao::get('ue~epreuve')->getByUe($row->id_ue);
                foreach ($Liste_epreuve as $row2) {
                    $tableau_epreuve[$row->id_semestre . '_' .$row2->id_epreuve] = $CounterEpreuve;
                    //$tableau_type_epreuve[$CounterEpreuve] = $row2->type_epreuve;
                    //asignier colone par epeuve
                    $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, $row2->type_epreuve);
                    $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(6);
                    //$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($CounterEpreuve,1, $row->code_ue . " - " .  $row->libelle);
                    //$tableau_Nom_Ue[$CounterEpreuve] = $row->code_ue . " - " .  $row->libelle ;
                    $CounterEpreuve ++;
                    
                }
                
                
    
                //rajouter une colone pour la moyenne de l'UE
                $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, $row->libelle);
                $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(7);
                //Rempli les moyenne avec une couleur
                //$Feuille->getStyle($this->Nums2Case($CounterEpreuve+1, $ligne_UE+1) . ":" . $this->Nums2Case($CounterEpreuve+1,$CounterEtudiant-1))->applyFromArray($styleArrayCouleurMoyenneUE);
                $Feuille->getStyle($this->Nums2Case($CounterEpreuve+1, $ligne_UE+1) . ":" . $this->Nums2Case($CounterEpreuve+1,$CounterEtudiant-1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($this->FillColor($row->id_ue));
    
                $CounterEpreuve ++;
                //rajouter une colone pour les credits de l'epreuve
                $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, "Crédits");
                $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(4);
                
                //on calcule le nom de colonne de moyenne avant de rentrer dans la boucle
                $coloneMoyenne = $this->Num2Colone($CounterEpreuve);
                for ($i = $DebutCounterEtudiant; $i < $CounterEtudiant; $i++) {
                    
                    //Le nom de fonction doit etre en anglais les arguments separe par des virgules
                    $Feuille->setCellValueByColumnAndRow($CounterEpreuve, $i ,'=IF('. $coloneMoyenne . $i .'>=10,'. $row->credits .',"")');// ca marche
                    
                    
                }

                
                
                //merge les celulues de titre par matirere
                $Feuille->mergeCells( $this->Nums2Case($conteurFusionTitre+2, $ligne_UE) . ":" . $this->Nums2Case($CounterEpreuve+1,$ligne_UE));
                //rempli la selule d'UE de la meme couleure que les moyennes
                $Feuille->getStyle($this->Nums2Case($conteurFusionTitre+2, $ligne_UE))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($this->FillColor($row->id_ue));
    
                $Feuille->getStyle($this->Nums2Case($conteurFusionTitre+2, $ligne_UE) . ":" . $this->Nums2Case($CounterEpreuve+1,$CounterEtudiant-1))->applyFromArray($styleArrayBordureUE);
                $Feuille->setCellValueByColumnAndRow($conteurFusionTitre+1,$ligne_UE, $row->code_ue . " - " .  $row->libelle);
                $conteurFusionTitre = $CounterEpreuve;
                
        
                $CounterEpreuve ++;
                
                
                
                
                
                
            } 
        

            //Gestion de Resultat semestre
            
            //rajouter une colone pour 
            $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, "Pt jury");
            $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(7);
            $CounterEpreuve ++;
            
            //rajouter une colone pour 
            $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, "Moy.");
            $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(7);
            $CounterEpreuve ++;
            
            //rajouter une colone pour 
            $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, "Crédits");
            $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(7);
            $CounterEpreuve ++;
            
                            //rajouter une colone pour 
            $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, "DECIS");
            $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(7);
            $CounterEpreuve ++;
            
            //rajouter une colone pour 
            $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, "mention");
            $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(7);
            $CounterEpreuve ++;
            
            //rajouter une colone pour 
            $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, "Crédits semestre");
            $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(7);
            $CounterEpreuve ++;
            
            //rajouter une colone pour 
            $Feuille->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, "CL");
            $Feuille->getColumnDimensionByColumn($CounterEpreuve)->setWidth(7);
            
            
            
            //merge les celulues de titre
            $Feuille->mergeCells( $this->Nums2Case($conteurFusionTitre+2, $ligne_UE) . ":" . $this->Nums2Case($CounterEpreuve+1,$ligne_UE));
            
                
            $Feuille->getStyle($this->Nums2Case($conteurFusionTitre+2, $ligne_UE) . ":" . $this->Nums2Case($CounterEpreuve+1,$CounterEtudiant-1))->applyFromArray($styleArrayBordureUE);
            $Feuille->setCellValueByColumnAndRow($conteurFusionTitre+1,$ligne_UE, 'Semestre');
            $conteurFusionTitre = $CounterEpreuve;
            
    
            $CounterEpreuve ++;
        }
        
        
        //print_r($tableau_epreuve);
        //print_r($tableau_type_epreuve);
        //print_r($tableau_Nom_Ue);
        
        
        
        
        
        
        
        
           //print_r($factory);
        //$formation = jDao::get('formations~formation')->getByCodeAnnee($this->param('formation'), $this->param('annee'));
        //$semestre = jDao::get('formations~semestre')->getByFormationNum($formation->id_formation, $this->param('semestre'));
        //$id_semestre = $semestre->id_semestre;
        //$id_epreuve = $this->param('epreuve');
        //    
        //    $data = array();
        //$liste = $factory->findAll(); 
        
        
    
        
        $factory_notes = jDao::get('ue~note');
        $conditions = jDao::createConditions();
        $conditions->addCondition('id_semestre', 'in', $tableau_semestre);  
        $liste_notes =  $factory_notes->findBy($conditions);
    
        
        
        foreach ($liste_notes as $row3) {
            $Feuille->setCellValueByColumnAndRow($tableau_epreuve[$row3->id_semestre . '_' .$row3->id_epreuve],$tableau_etudiant_semestre[$row3->num_etudiant], $row3->valeur);
            $objPHPExcel->addNamedRange( new PHPExcel_NamedRange('H_'.$row3->id_epreuve ."_". $row3->num_etudiant   ."_".  $row3->id_semestre                , $Feuille, $this->Nums2Case($tableau_epreuve[$row3->id_epreuve]+1,$tableau_etudiant_semestre[$row3->num_etudiant])) );
        }
        
        
        //Met les titre pour le colone info Etudient
        $Feuille->setCellValueByColumnAndRow(0,$ligne_epreuve, "Nom");
        $Feuille->setCellValueByColumnAndRow(1,$ligne_epreuve, "Prenom");
        $Feuille->setCellValueByColumnAndRow(2,$ligne_epreuve, "N°dossier");
        $Feuille->setCellValueByColumnAndRow(3,$ligne_epreuve, "origine");
        $Feuille->setCellValueByColumnAndRow(4,$ligne_epreuve, "EXT/L");
        $Feuille->setCellValueByColumnAndRow(5,$ligne_epreuve, "CONTRAT");
        $Feuille->setCellValueByColumnAndRow(6,$ligne_epreuve, "Semestre manquant");
        

        
        
        //remplire la liste d'etudients
        $factoryEtudiants = jDao::get('etudiants~etudiants');
        
        $conditions = jDao::createConditions();
        $conditions->addCondition('num_etudiant', 'in', $tableau_etudiant);
        $listEtudiants = $factoryEtudiants->findBy($conditions);
        
        //jLog::dump($listEtudiants,'Tableau etidient');
        foreach ($listEtudiants as $row4) {
            
            //$data .= $row->valeur . "<br>";
            $Feuille->setCellValueByColumnAndRow(0,$tableau_etudiant_semestre[$row4->num_etudiant], $row4->nom);
            $Feuille->setCellValueByColumnAndRow(1,$tableau_etudiant_semestre[$row4->num_etudiant], $row4->prenom);
            $Feuille->setCellValueByColumnAndRow(2,$tableau_etudiant_semestre[$row4->num_etudiant], $row4->num_etudiant);
            //$objPHPExcel->addNamedRange( new PHPExcel_NamedRange('H_'.$row3->id_epreuve ."_". $row3->num_etudiant   ."_".  $row3->id_semestre                , $Feuille, $this->Nums2Case($tableau_epreuve[$row3->id_epreuve]+1,$tableau_etudiant_semestre[$row3->num_etudiant])) );
        }
        
        //on fixe les paneaux
        $Feuille->freezePane($this->Nums2Case(4,4));    
        

        
        
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
        
        
        

    }  
    public function widget_exportformation() {
        $form = jForms::fill('phpexcel~widget_exportformation');
        $id_formation = $form->getData('id_formation');
        
        $rep = $this->getResponse('redirect');        
        $rep->action = 'phpexcel~exportFormation';
        $rep->params = array('id' => $id_formation);
        return $rep;
    }
}