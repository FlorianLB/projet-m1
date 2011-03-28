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
        $etudiant = jDao::createRecord('etudiants~etudiants');
        $etudiantSemestre = jDao::createRecord('etudiants~etudiants_semestre');
        $formation = jDao::createRecord('formations~formation');
        $formationSemestre = jDao::createRecord('formations~semestre');
        $semestre = jDao::createRecord('formations~semestre');
        $semestreUe = jDao::createRecord('formations~semestre_ue');
        $ue = jDao::createRecord('ue~ue');
        
        if (($handle = fopen($this->fichier, "r")) !== FALSE) {
            while (($line = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                
                //si on est a la premiere ligne on lit la formation ainsi que l'année
                if($compteur = 1){
                    
                    //on cree la formation case 0
                    //de cette année
                    //la verif est elle importante ??????????
                    $factoryformation->code_formation = $line[0];
                    $factoryformation->annee = $line[1];
                    
                    // creation des semestre correspondant
                }
                
                
                elseif($compteur = 4){
                    //on cree un tableau pour chaque case contenant une UE
                    $colone = 0;
                    while($line($colone)!= "stop"){
                        //si l'ue n'est pas deja cree on la cree
                        if($colone > 0 && $line($colone) != $line($colone-1)){
                                //si les ue font partie du deuxieme semestre
                            if($line($colone) = "next"){
                                $colonne_semestre = $colone+1;
                            }
                            
                            ///creation de l'ue et liaison au semestre en fonction du semestre correspondant
                            
                            
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