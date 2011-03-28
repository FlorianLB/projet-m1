<?php

jClasses::inc('utils~customSQL');

class NotePv{
    
    public $fichier;
    
    //Tableau comprenant toutes les formations gérées par le secretariat
    public $formations = array();
    
    
    function __construct($fichier){
        $this->fichier = $fichier;
        
        //foreach(customSQL::findAllDistinctCodeFormation() as $f){
        //    $this->formations[] = $f->code_formation;
        //}

    }
    
    /**
     * Parse le fichier CSV Apogée
     *
     * @param boolean $only_identite Extraction faites uniquement sur l'identite de l'etudiant
     */
    public function parse($only_identite = true, $delimiter = ";" ){
        
        $data = array();
        $liste_eu = array(array());
        $compteur = 1;
        $colonne_semestre;
        
        //$etudiant = jDao::createRecord('etudiants~etudiants');
        //$etudiantSemestre = jDao::createRecord('etudiants~etudiants_semestre');
        $factoryFormation = jDao::get('formations~formation');
        //$formationSemestre = jDao::createRecord('formations~semestre');
        $factorySemestre = jDao::get('formations~semestre');
        $factorySemestreUe = jDao::get('formations~semestre_ue');
        //$ue = jDao::createRecord('ue~ue');
        //$epreuve = jDao::createRecord('ue~epreuve');
        //$note = jDao::createRecord('ue~note');
        
        if (($handle = fopen($this->fichier, "r")) !== FALSE) {
            while (($line = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                
                //si on est a la premiere ligne on lit la formation ainsi que l'année
                if($compteur = 1){
                    
                    //on cree la formation case 0
                    //de cette année
                    //la verif est elle importante ??????????
                    $formation = jDao::createRecord('formations~formation');
                    $formation->code_formation = $line[0];
                    $formation->annee = $line[1];
                    $factoryFormation->insert($formation);
                    // creation des semestre correspondant
                    $semestre1 = jDao::createRecord('formations~semestre');
                    $semestre1->id_formation = $formation->id_formation;
                    $semestre1->num_semestre = 1;
                    
                    //On créer le 2eme
                    $semestre2 = jDao::createRecord('formations~semestre');
                    $semestre2->id_formation = $formation->id_formation;
                    $semestre2->num_semestre = 2;
                    
                    // On insère via la factory DAO
                    $factorySemestre = jDao::get('formations~semestre');
                    $factorySemestre->insert($semestre1);
                    $factorySemestre->insert($semestre2);
                }
                
                
                elseif($compteur = 4){
                    //on cree un tableau pour chaque case contenant une UE
                    $colone = 0;
                    while($line[colone])!= "stop"){
                        //si l'ue n'est pas deja cree on la cree
                        if($colone > 0 && $line[$colone] != $line[$colone-1]){
                                //si les ue font partie du deuxieme semestre
                            if($line($colone) = "next"){
                                $colonne_semestre = $colone+1;
                            }
                            $ue = jDao::createRecord('ue~ue');
                            $ue->code_ue = $line[$colone];
                            $ue->credit = 1;
                            $ue->coeff = 1;
                            $ue->last_version = FALSE;
                            $factoryUe->insert($ue);
                            ///creation de l'ue et liaison au semestre en fonction du semestre correspondant
                            $semestre_ue = jDao::createRecord('formations~semestre_ue');
                            
                            if($colone < $colonne_semestre){
                                $semestre_ue->id_semestre = $semestre1->id_semestre;
                            }
                            else{
                                $semestre_ue->id_semestre = $semestre2->id_semestre;
                            }
                            $semestre_ue->id_ue = $ue->id_ue;
                            $semestre_ue->optionelle = FAlSE;
                            $factorySemestreUe->insert($semestre_ue);
                            
                            
                        }
                        $liste_eu[$colone][ue] = $line($colone);
                    }
                }
                 elseif($compteur = 5){
                    //on enregistre chaque epreuve
                    $liste_eu[$colone][epreuve] = $line($colone);
                    //creation des epreuves
                 }
                 
                
                else{
    
                    //if l'etudiant n'existe pas deja
                    // on cree l'etudiant
                    // ensuite on prend chaqune des cases non vides
                    // et on cree une notes sur cette epreuve
                    numEtudiant = $line[0];
                    //...
                    
                    // si le num etudiant n'est pas la on fini la lecture
                        $data[] = $etu;
                    }
                    else
                        $data[] = $line;
                }

                
                
            }
            fclose($handle);
        }
        
        return $data;
    }
    
}