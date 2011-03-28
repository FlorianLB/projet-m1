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
        
        $liste_ue = array(array());
        $compteur = 0;
        $colonne_semestre;
        $arret = "stop";
        $semestre_suivant = "next";
        $factoryEtudiant = jDao::get('etudiants~etudiants');
        $factoryEtudiantSemestre = jDao::get('etudiants~etudiants_semestre');
        $factoryFormation = jDao::get('formations~formation');
        $factorySemestre = jDao::get('formations~semestre');
        $factorySemestreUe = jDao::get('formations~semestre_ue');
        $factoryUe = jDao::get('ue~ue');
        $factoryEpreuve = jDao::get('ue~epreuve');
        $factoryNote = jDao::get('ue~note');
        
        if (($handle = fopen($this->fichier, "r")) !== FALSE) {
            //boucle sur les lignes du fichier
            while (($line = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                $compteur = $compteur+1;
                jLog::dump(120);
                //si on est a la premiere ligne on lit la formation ainsi que l'année
                if($compteur = 1){
                    
                    //on cree la formation case 0
                    //de cette année
                    //TODO ?la verif est elle importante ??????????
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
                    $colonne = 0;
                    //boucle sur les colonne du fichier
                    while($line[$colonne]!= $arret){
                        //si l'ue n'est pas deja cree on la cree
                        if($colonne > 0 && $line[$colonne] != $line[$colonne-1]){
                                //si les ue font partie du deuxieme semestre
                            if($line[$colonne] = $semestre_suivant){
                                $colonne_semestre = $colonne+1;
                            }
                            $ue = jDao::createRecord('ue~ue');
                            $ue->code_ue = $line[$colonne];
                            $ue->credit = 1;
                            $ue->coeff = 1;
                            $ue->last_version = FALSE;
                            $factoryUe->insert($ue);
                            ///creation de l'ue et liaison au semestre en fonction du semestre correspondant
                            $semestre_ue = jDao::createRecord('formations~semestre_ue');
                            
                            if($colonne < $colonne_semestre){
                                $semestre_ue->id_semestre = $semestre1->id_semestre;
                            }
                            else{
                                $semestre_ue->id_semestre = $semestre2->id_semestre;
                            }
                            $semestre_ue->id_ue = $ue->id_ue;
                            $semestre_ue->optionelle = FAlSE;
                            $factorySemestreUe->insert($semestre_ue);
                            
                            
                        }
                        // on enregistre en fonction de la colonne l'id_ue
                        $liste_ue[$colonne]["id_ue"] = $ue->id_ue;
                        $colonne++;
                    }
                }
                 elseif($compteur = 5){
                    
                    $colonne = 0;
                    //boucle sur les colonne du fichier
                    while($line[$colonne]!= $arret){
                        //creation des epreuves
                        $epreuve = jDao::createRecord('ue~epreuve');
                        $epreuve->id_ue = $liste_ue[$colonne]["id_ue"];
                        $epreuve->coeff = 1;
                        $epreuve->type_epreuve = $line[$colonne];
                        $factoryEpreuve->insert($epreuve);
                        
                        //on enregistre chaque epreuve en fonction de la colonne
                        $liste_ue[$colonne]["id_epreuve"] = $epreuve->id_epreuve;
                        $colonne++;
                    }
                }
                 
                
                else{
                    //si l'etudiant n'existe pas on le cree
                    if(!customSQL::etudiantsExisteDeja($line[2])){
                        $etudiant = jDao::createRecord('etudiants~etudiants');
                        $etudiant->num_etudiant = $line[2];
                        $etudiant->nom = $line[0];
                        $etudiant->prenom = $line[1];
                        //TODO aucune info sur le sexe ... il faut le metre en non require
                        $etudiant->sexe = "M";
                        $factoryEtudiant->insert($etudiant);
                    }
                    
                    //// ajout au 2 semestre par defaut
                    //TODO voir si ca pose des problemes
                    $etudiantSemestre1 = jDao::createRecord('etudiants~etudiants_semestre');
                    $etudiantSemestre2 = jDao::createRecord('etudiants~etudiants_semestre');
                    
                    $etudiantSemestre1->num_etudiant = $etudiant->num_etudiant;
                    $etudiantSemestre2->num_etudiant = $etudiant->num_etudiant;
                    
                    $etudiantSemestre1->id_semestre = $semestre1->id_semestre;
                    $etudiantSemestre2->id_semestre = $semestre2->id_semestre;
                    
                    $factoryEtudiantSemestre->insert($etudiantSemestre1);
                    $factoryEtudiantSemestre->insert($etudiantSemestre2);
                    
                    //les notes commence a la case 8-1
                    //boucle sur les colonne du fichier
                    $colonne = 7;
                    while($line[$colonne] != $arret){
                        if($line[$colonne] != ""){
                            $note = jDao::createRecord('ue~note');
                            $note->epreuve = $liste_ue[$colonne]["id_epreuve"];
                            //on assigne le semestre de la note en fonction du placement dans la colonne
                            if($colonne < $colonne_semestre){
                                $note->semestre = $semestre1->id_semestre;
                            }
                            else{
                                $note->semestre = $semestre2->id_semestre;
                            }
                            
                            $note->num_etudiant = $etudiant->num_etudiant;
                            if($line[$colonne] == "abs"){
                                $note->valeur = 0;
                            }
                            else{
                                $note->valeur = $line[$colonne];
                            }
                            $factoryNote->insert($note);
                            
                        }
                        $colonne++;
                    }
                    
                    
                    //
                    //$etudiantSemestre = jDao::createRecord('etudiants~etudiants_semestre');
                    ////if l'etudiant n'existe pas deja
                    //// on cree l'etudiant
                    ////on l'ajoute au semestre de l'ue
                    //// ensuite on prend chaqune des cases non vides
                    //// et on cree une notes sur cette epreuve
                    //$numEtudiant = $line[0];
                    ////...
                    //
                    //// si le num etudiant n'est pas la on fini la lecture
                    //    $data[] = $etu;
                    //}
                    //else
                    //    $data[] = $line;
                }

                
               
            }
            fclose($handle);
        }
        
       // return $data;
    }
    
}