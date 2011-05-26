<?php
/**
* @package   ogre
* @subpackage phpexcel
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/
require_once (LIB_PATH.'phpexcel/PHPExcel.php');
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
        jClasses::inc('utils~customSQL');
        jClasses::inc('utils~Moyenne');    
            
        $id = $this->param('id', 0);
        
        $ligne_UE = 2;
        $ligne_epreuve = 3;
        $DebutCounterEtudiant = $ligne_epreuve + 1;

        $colonesTpDuJurry = array();
        $colonesMoyennesSemestre = array();
        $Coef_UE = array();
        
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
        $FeuilleSession1 = $objPHPExcel->setActiveSheetIndex(0);
        //on cree une dexieme feuille pour la dexieme session
        $objPHPExcel->createSheet();
        $FeuilleSession2 = $objPHPExcel->setActiveSheetIndex(1);
        //on agrandit la taille de la ligne pour afficher plus d'info
        $FeuilleSession1->getRowDimension($ligne_epreuve)->setRowHeight(55);
        //TODO Force cette taille ca si un nom est tros grand sa deborde
        $FeuilleSession1->getStyle('3')->applyFromArray($styleArrayAlignmentLigneEpreuve);
        
        //recupere les deux semestre de la formation
        $Liste_semestres = jDao::get('formations~semestre')->getByFormation($id);
        $tableau_semestre = array();
        $tableau_semestre_num_semestre = array();
        foreach ($Liste_semestres as $semestre) {
            $tableau_semestre[] = $semestre->id_semestre;
            $tableau_semestre_num_semestre[$semestre->id_semestre]=$semestre->num_semestre;
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
                //$FeuilleSession1->setCellValueByColumnAndRow(0,$CounterEtudiant, $row->num_etudiant);
                
                $tableau_etudiant[] = $row->num_etudiant;
                $CounterEtudiant ++;
            }

        }
        

        
        
        //print_r($tableau_etudiant_semestre);
        //jLog::dump($tableau_etudiant,'Tableau etidient');
        
        
        //on  prepar les variables
        $tableau_epreuve = array();
        $tableau_MoyenneUE = array();//[$row->id_semestre . '_' .$row2->id_epreuve]
        $CounterEpreuve = 7; //on laise das case pour les etudients num , nom, premom
        
        
               
        
        
        foreach ($tableau_semestre as $unsemestre){
            $conteurFusionTitre = $CounterEpreuve-1;  //oninitialise la variable de calcul de fusion
            //recuperer liste epreuve par semestre
            //recupere la liste des ue qui corespondent au semestre
            $Liste_ue = jDao::get('ue~ue_semestre_ue')->getBySemestre($unsemestre);
            
    
            //$tableau_type_epreuve = array();
            //$tableau_Nom_Ue = array();
            foreach ($Liste_ue as $row) {
                $Coef_UE[$row->id_ue] = $row->coeff;
                //pour chaque ue recuperles les id epreuve qui corespondent
                $Liste_epreuve = jDao::get('ue~epreuve')->getByUe($row->id_ue);
                foreach ($Liste_epreuve as $row2) {
                    $tableau_epreuve[$row->id_semestre . '_' .$row2->id_epreuve] = $CounterEpreuve;
                    //$tableau_type_epreuve[$CounterEpreuve] = $row2->type_epreuve;
                    //asignier colone par epeuve
                    $FeuilleSession1->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, $row2->type_epreuve);
                    $FeuilleSession1->getColumnDimensionByColumn($CounterEpreuve)->setWidth(6);
                    //$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($CounterEpreuve,1, $row->code_ue . " - " .  $row->libelle);
                    //$tableau_Nom_Ue[$CounterEpreuve] = $row->code_ue . " - " .  $row->libelle ;
                    $CounterEpreuve ++;
                    
                }
                
                
    
                //rajouter une colone pour la moyenne de l'UE
                $FeuilleSession1->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, $row->libelle);
                $FeuilleSession1->getColumnDimensionByColumn($CounterEpreuve)->setWidth(7);
                
                //Rempli les moyenne avec une couleur
                //$FeuilleSession1->getStyle($this->Nums2Case($CounterEpreuve+1, $ligne_UE+1) . ":" . $this->Nums2Case($CounterEpreuve+1,$CounterEtudiant-1))->applyFromArray($styleArrayCouleurMoyenneUE);
                $FeuilleSession1->getStyle($this->Nums2Case($CounterEpreuve+1, $ligne_UE+1) . ":" . $this->Nums2Case($CounterEpreuve+1,$CounterEtudiant-1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($this->FillColor($row->id_ue));
                $tableau_MoyenneUE[$row->id_semestre . '_' .$row->id_ue]=$CounterEpreuve;
                
                $CounterEpreuve ++;
                //rajouter une colone pour les credits de l'epreuve
                $FeuilleSession1->setCellValueByColumnAndRow($CounterEpreuve,$ligne_epreuve, "Crédits");
                $FeuilleSession1->getColumnDimensionByColumn($CounterEpreuve)->setWidth(4);
                
                //on calcule le nom de colonne de moyenne avant de rentrer dans la boucle
                $coloneMoyenne = $this->Num2Colone($CounterEpreuve);
                for ($i = $DebutCounterEtudiant; $i < $CounterEtudiant; $i++) {
                    
                    //Le nom de fonction doit etre en anglais les arguments separe par des virgules
                    //formul credit
                    $FeuilleSession1->setCellValueByColumnAndRow($CounterEpreuve, $i ,'=IF(AND('. $coloneMoyenne . $i .'>=10,'. $coloneMoyenne . $i .'<20),'. $row->credits .',"")');// ca marche
                    
                }

                
                
                //merge les celulues de titre par matirere
                $FeuilleSession1->mergeCells( $this->Nums2Case($conteurFusionTitre+2, $ligne_UE) . ":" . $this->Nums2Case($CounterEpreuve+1,$ligne_UE));
                //rempli la selule d'UE de la meme couleure que les moyennes
                $FeuilleSession1->getStyle($this->Nums2Case($conteurFusionTitre+2, $ligne_UE))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($this->FillColor($row->id_ue));
    
                $FeuilleSession1->getStyle($this->Nums2Case($conteurFusionTitre+2, $ligne_UE) . ":" . $this->Nums2Case($CounterEpreuve+1,$CounterEtudiant-1))->applyFromArray($styleArrayBordureUE);
                $FeuilleSession1->setCellValueByColumnAndRow($conteurFusionTitre+1,$ligne_UE, $row->code_ue . " - " .  $row->libelle);
                $conteurFusionTitre = $CounterEpreuve;
                
        
                $CounterEpreuve ++;
                
                
                
                
                
                
            } 
        

            //Gestion de Resultat semestre
            
            //rajouter une colone pour les point du jurry
            //on calcule le nom de la colonne
            $coloneTpDuJurry = $this->Num2Colone($CounterEpreuve+1);
            $FeuilleSession1->setCellValue($coloneTpDuJurry . $ligne_epreuve, "Pt jury");
            $FeuilleSession1->getColumnDimension($coloneTpDuJurry)->setWidth(7);
            //sauve la colone des point du jurry car on en a besoitplus tard pour les celules nomee
            $colonesTpDuJurry[] = $coloneTpDuJurry;
            $CounterEpreuve ++;
            
            //rajouter une colone pour la moyenne
            //on calcule le nom de la colonne
            $coloneMoyenne = $this->Num2Colone($CounterEpreuve+1);
            $FeuilleSession1->setCellValue($coloneMoyenne . $ligne_epreuve, "Moy.");
            $FeuilleSession1->getColumnDimension($coloneMoyenne)->setWidth(7);
            $colonesMoyennesSemestre[] = $coloneMoyenne;
            $CounterEpreuve ++;
            
            //rajouter une colone pour les credit
            //on calcule le nom de la colonne
            $coloneCredit = $this->Num2Colone($CounterEpreuve+1);
            $FeuilleSession1->setCellValue($coloneCredit . $ligne_epreuve, "Crédits");
            $FeuilleSession1->getColumnDimension($coloneCredit)->setWidth(7);
            $CounterEpreuve ++;
            
            //rajouter une colone pour la desition du jury
            //on calcule le nom de la colonne
            $coloneDECIS = $this->Num2Colone($CounterEpreuve+1);
            $FeuilleSession1->setCellValue($coloneDECIS . $ligne_epreuve, "DECIS");
            $FeuilleSession1->getColumnDimension($coloneDECIS)->setWidth(7);
            $CounterEpreuve ++;
            
            //rajouter une colone pour la mention
            //on calcule le nom de la colonne
            $coloneMention = $this->Num2Colone($CounterEpreuve+1);
            $FeuilleSession1->setCellValue($coloneMention . $ligne_epreuve, "mention");
            $FeuilleSession1->getColumnDimension($coloneMention)->setWidth(7);
            $CounterEpreuve ++;
            
            //rajouter une colone pour les credit du semestre
            //on calcule le nom de la colonne
            $coloneCreditSemestre = $this->Num2Colone($CounterEpreuve+1);
            $FeuilleSession1->setCellValue($coloneCreditSemestre . $ligne_epreuve, "Crédits semestre");
            $FeuilleSession1->getColumnDimension($coloneCreditSemestre)->setWidth(7);

            $CounterEpreuve ++;
            
            //rajouter une colone pour le Classement
            //on calcule le nom de la colonne
            $coloneCL = $this->Num2Colone($CounterEpreuve+1);
            $FeuilleSession1->setCellValue($coloneCL . $ligne_epreuve, "CL");
            $FeuilleSession1->getColumnDimension($coloneCL)->setWidth(7);
            
            
            
            
            //merge les celulues de titre
            $FeuilleSession1->mergeCells( $this->Nums2Case($conteurFusionTitre+2, $ligne_UE) . ":" . $this->Nums2Case($CounterEpreuve+1,$ligne_UE));
            
                
            $FeuilleSession1->getStyle($this->Nums2Case($conteurFusionTitre+2, $ligne_UE) . ":" . $this->Nums2Case($CounterEpreuve+1,$CounterEtudiant-1))->applyFromArray($styleArrayBordureUE);
            $FeuilleSession1->setCellValueByColumnAndRow($conteurFusionTitre+1,$ligne_UE, 'Semestre');
            $conteurFusionTitre = $CounterEpreuve;
            
            
            //on calcule le nom de colonne de moyenne avant de rentrer dans la boucle
            //$coloneMoyenne = $this->Num2Colone($CounterEpreuve);
            for ($i = $DebutCounterEtudiant; $i < $CounterEtudiant; $i++) {
                //Le nom de fonction doit etre en anglais les arguments separe par des virgules
                $FeuilleSession1->setCellValue($coloneDECIS . $i ,'=IF('. $coloneMoyenne . $i .'>=10,"ADMIS(E)","AJOURNE(E)")');// ca marche
                $FeuilleSession1->setCellValue($coloneMention . $i ,'=IF('. $coloneMoyenne . $i .'>=10,IF('. $coloneMoyenne . $i .'<12,"PASABLE",IF('. $coloneMoyenne . $i .'<14,"Assez Bien",IF('. $coloneMoyenne . $i .'<16,"Bien","Très Bien"))),"")');// ca marche
                $FeuilleSession1->setCellValue($coloneCreditSemestre . $i ,'=IF('. $coloneMoyenne . $i .'>=10,30,' . $coloneCredit.$i . ')');// ca marche
                $FeuilleSession1->setCellValue($coloneCL . $i ,'=RANK('. $coloneMoyenne . $i .',' . $coloneMoyenne. '$' .$DebutCounterEtudiant . ':' .  $coloneMoyenne. '$' . ($CounterEtudiant -1) .  ')');// ca marche
                //##############PT JURRY######
                //on rejoute les celulenommer pour  les PT du jurry
                //Atention on recuper le numero a partire de la celule du tableux
                //$numetudient = $FeuilleSession1->getCell('C'.$i)->getValue(); 
                //ou A partir du tableau
                //$numetudient = array_search($i, $listEtudiants,true);
                //TODO  Choisier la meillere solution en temps               
                //$objPHPExcel->addNamedRange( new PHPExcel_NamedRange('H_PTJURRY_'. $numetudient   ."_".  $row3->id_semestre                , $FeuilleSession1, $this->Nums2Case($tableau_epreuve[$row3->id_epreuve]+1,$tableau_etudiant_semestre[$row3->num_etudiant])) );
                //ou plus tard avec sauvegarde des zone de TP du jury
                //##############Calculle avec info etudient
                
            }
            
            
    
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
            if($row3->valeur == -1){
                $FeuilleSession1->setCellValueByColumnAndRow($tableau_epreuve[$row3->id_semestre . '_' .$row3->id_epreuve],$tableau_etudiant_semestre[$row3->num_etudiant], 'ABS');
        }else{
                $FeuilleSession1->setCellValueByColumnAndRow($tableau_epreuve[$row3->id_semestre . '_' .$row3->id_epreuve],$tableau_etudiant_semestre[$row3->num_etudiant], $row3->valeur);
            }
            
            $objPHPExcel->addNamedRange( new PHPExcel_NamedRange('H_'.$row3->id_epreuve ."_". $row3->num_etudiant   ."_".  $row3->id_semestre                , $FeuilleSession1, $this->Nums2Case($tableau_epreuve[$row3->id_semestre . '_' .$row3->id_epreuve]+1,$tableau_etudiant_semestre[$row3->num_etudiant])) );
        }
        
        
        //Met les titre pour le colone info Etudient
        $FeuilleSession1->setCellValueByColumnAndRow(0,$ligne_epreuve, "Nom");
        $FeuilleSession1->setCellValueByColumnAndRow(1,$ligne_epreuve, "Prenom");
        $FeuilleSession1->setCellValueByColumnAndRow(2,$ligne_epreuve, "N°dossier");
        $FeuilleSession1->setCellValueByColumnAndRow(3,$ligne_epreuve, "origine");
        $FeuilleSession1->setCellValueByColumnAndRow(4,$ligne_epreuve, "EXT/L");
        $FeuilleSession1->setCellValueByColumnAndRow(5,$ligne_epreuve, "CONTRAT");
        $FeuilleSession1->setCellValueByColumnAndRow(6,$ligne_epreuve, "Semestre manquant");
        //Met les titre pour le colone info Etudient
        $FeuilleSession2->setCellValueByColumnAndRow(0,$ligne_epreuve, "Nom");
        $FeuilleSession2->setCellValueByColumnAndRow(1,$ligne_epreuve, "Prenom");
        $FeuilleSession2->setCellValueByColumnAndRow(2,$ligne_epreuve, "N°dossier");
        $FeuilleSession2->setCellValueByColumnAndRow(3,$ligne_epreuve, "origine");
        $FeuilleSession2->setCellValueByColumnAndRow(4,$ligne_epreuve, "EXT/L");
        $FeuilleSession2->setCellValueByColumnAndRow(5,$ligne_epreuve, "CONTRAT");
        $FeuilleSession2->setCellValueByColumnAndRow(6,$ligne_epreuve, "Semestre manquant");
       

        

        
        if ($CounterEtudiant == $DebutCounterEtudiant){
            //jMessage::add("il n'y a pas d'etudiant dans cette formation", 'error');
            //si il n'y a pas d'etudiant on fait une liste vide
            $listEtudiants = array();
        }else{
            //remplire la liste d'etudients
            $factoryEtudiants = jDao::get('etudiants~etudiants');
            $conditions = jDao::createConditions();
            $conditions->addCondition('num_etudiant', 'in', $tableau_etudiant);
            $listEtudiants = $factoryEtudiants->findBy($conditions);
        }
        
        
        foreach ($listEtudiants as $row4) {
            //$data .= $row->valeur . "<br>";
            $FeuilleSession1->setCellValue('A'.$tableau_etudiant_semestre[$row4->num_etudiant], $row4->nom);
            $FeuilleSession1->setCellValue('B'.$tableau_etudiant_semestre[$row4->num_etudiant], $row4->prenom);
            $FeuilleSession1->setCellValue('C'.$tableau_etudiant_semestre[$row4->num_etudiant], $row4->num_etudiant);
            
            //on rajoute aussi les case TP du Jurry ici car on a acess tres facilement au valeur  
            $objPHPExcel->addNamedRange( new PHPExcel_NamedRange('H_PTJURRY_'. $row4->num_etudiant   .'_'.  1 , $FeuilleSession1, $colonesTpDuJurry[0] . $tableau_etudiant_semestre[$row4->num_etudiant] ) );
            $objPHPExcel->addNamedRange( new PHPExcel_NamedRange('H_PTJURRY_'. $row4->num_etudiant   .'_'.  2 , $FeuilleSession1, $colonesTpDuJurry[1] . $tableau_etudiant_semestre[$row4->num_etudiant] ) );
            
            
            
            //moyennetotal
            $objPHPExcel->addNamedRange( new PHPExcel_NamedRange('H_MoyGen_'. $row4->num_etudiant   .'_'.  1 , $FeuilleSession1, $colonesMoyennesSemestre[0] . $tableau_etudiant_semestre[$row4->num_etudiant] ) );
            $objPHPExcel->addNamedRange( new PHPExcel_NamedRange('H_MoyGen_'. $row4->num_etudiant   .'_'.  2 , $FeuilleSession1, $colonesMoyennesSemestre[1].$tableau_etudiant_semestre[$row4->num_etudiant] ) );
            
            //on met a dispence toute les moyenne
            //jLog::dump($tableau_MoyenneUE,"$tableau_MoyenneUE");
            foreach($tableau_MoyenneUE as $uncasedemoyennedeue){
                $FeuilleSession1->setCellValueByColumnAndRow($uncasedemoyennedeue,$tableau_etudiant_semestre[$row4->num_etudiant], "OPTION");              
            }
            //$colonesMoyennesSemestre
            $moyennesEtudiantSemestreTotal = "";
            foreach ($tableau_semestre as $unsemestre){
               $moyennesEtudiantSemestre = Moyenne::calcAllMoyenne($unsemestre,$row4->num_etudiant);
                //si il n'es pas dispencee on met la moyenne
                //jLog::dump($Coef_UE,'$Coef_UE');
                $fdsfdsmoyenne = "=((";
                $coeftotal = 0; 
                foreach ($moyennesEtudiantSemestre as $id_ueetudient => $moyenneue) {
                    
                    //jLog::log("-->".$moyenneue . "<--moyenneue"  );
                    if($moyenneue === false){
                         $FeuilleSession1->setCellValueByColumnAndRow($tableau_MoyenneUE[$unsemestre . '_' .$id_ueetudient],$tableau_etudiant_semestre[$row4->num_etudiant], "DIS");
                    }else{
                        $FeuilleSession1->setCellValueByColumnAndRow($tableau_MoyenneUE[$unsemestre . '_' .$id_ueetudient],$tableau_etudiant_semestre[$row4->num_etudiant], $moyenneue);
                        $coeftotal = $coeftotal + $Coef_UE[$id_ueetudient];
                        
                        $fdsfdsmoyenne = $fdsfdsmoyenne . $moyenneue . '*' . $Coef_UE[$id_ueetudient]. '+' ;
                    }
                    //if ($moyenneue != false ){

                        
                    //}
                    
                    
                }
                //remplace le plus pas divise et ajouter le total de div
                $fdsfdsmoyenne = substr_replace($fdsfdsmoyenne,')/'.$coeftotal.")+" . $colonesTpDuJurry[$tableau_semestre_num_semestre[$unsemestre]-1].$tableau_etudiant_semestre[$row4->num_etudiant],-1);
                $FeuilleSession1->setCellValue($colonesMoyennesSemestre[$tableau_semestre_num_semestre[$unsemestre]-1].$tableau_etudiant_semestre[$row4->num_etudiant],$fdsfdsmoyenne);
            }
            
            
            
        }

        //on fixe les paneaux
        $FeuilleSession1->freezePane($this->Nums2Case(4,4));    
        $FeuilleSession2->freezePane($this->Nums2Case(4,4));

        
        
        // Rename sheet
        $FeuilleSession1->setTitle('1ere session');
        $FeuilleSession2->setTitle('2eme session');
        
        

        
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