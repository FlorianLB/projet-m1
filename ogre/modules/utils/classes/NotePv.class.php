<?php

jClasses::inc('utils~customSQL');

class NotePv{
    
    public $fichier;
    
    //Tableau comprenant toutes les formations gérées par le secretariat
    public $formations = array();
    
    
    function __construct($fichier){
        $this->fichier = $fichier;
    }
    
    /**
     ** @param ligne , array d'indice de colonne (S1, S2) , indice descion année
     ** @return array de descision
     ** 
     ***/
    
    public function decision($line,$colonne_decision,$colonne_decision_annee){
        
        $i=0;
        $reponse;
        
            if($line[$colonne_decision_annee] !== "AJAC"){
                switch($line[$colonne_decision]){
                    case "AJOURNE(E)" : $reponse = "AJO";
                    break;
                    case "ADMIS(E)" : $reponse = "ADM";
                    break;
                
                //TODO mettre un exeption si on entre dans le defautl
                }
            }
            else{
                switch($line[$colonne_decision]){
                case "AJOURNE(E)" : $reponse = "AJC";
                break;
                case "ADMIS(E)" : $reponse = "ADM";
                break;
            }
            
        }
        return $reponse;
    }
    
    /**
     * Parse le fichier CSV Pv
     *
     * @param boolean $only_identite Extraction faites uniquement sur l'identite de l'etudiant
     */
    public function parse($only_identite = true, $delimiter = ";" ){
        
        $liste_ue = array(array());
        $compteur = 0;
        $nbajout = 0;
        // colonne qui definit le changement de semestre
        $colonne_semestre = 0;
        //colonne qui definit la fin de lecture
        $colonne_arret;
        $colonne_decision1 =0;
        $colonne_decision2 =0;
        $arret = "stop";
        $seme1 = 'SEMESTRE 1';
        $seme2 = 'SEMESTRE 2';
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
                //si on est a la premiere ligne on lit la formation ainsi que l'année
                switch($compteur){
                    case 3 : $colonne=0;
                        while($line[$colonne] != $arret){

                            //initialisation du tableau
                            $liste_ue[$colonne]["id_ue"] = -1;
                            $liste_ue[$colonne]["epreuve"] = -1;
    
                            // on recherche la colonne de changement de semestre
                            //on sauvegarde l'endroit de la descision de la reussite du semestre 1 et 2
                           //if(!strcmp($line[$colonne], $semestre1)){
                             if($line[$colonne] == $seme1){
                                    $colonne_semestre = $colonne;
                                    $colonne_decision1 = $colonne;
                                    
                            }
                            elseif($line[$colonne] == $seme2){
                                    $colonne_decision2 = $colonne;
                                    
                            }
                            $colonne++;
                        }
                        
                        $colonne_arret = $colonne;
                    break;
                    //choix 2 inutile pour le moment
                    case 2 : break;
                    
                    case 1 :
                    
                    //on cree la formation case 0
                    //de cette année
                    if(!customSQL::formationExisteDeja($line[0],$line[1])){
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
                        
                        //TODO inséré compensation semestre
                    }
                    //sinon on va la cherché dans la base
                    else{
                        $formation = jDao::get('formations~formation')->getByCodeAnnee($line[0],$line[1]);
                        $semestre1 = jDao::get('formations~semestre')->getByFormationNum($formation->id_formation,1);
                        $semestre2 = jDao::get('formations~semestre')->getByFormationNum($formation->id_formation,2);
                    }
                    break;
                
                
                    //si on est su la ligne 4 on lit les ues
                    case 4 :
                    //on cree un tableau pour chaque case contenant une UE
                        $colonne = 6;
                        //boucle sur les colonne du fichier
                        while($colonne < $colonne_arret){
                            //si l'ue n'est pas deja cree on la cree
                            if($line[$colonne] != $line[$colonne-1]){                            
                                //si le code est different du separateur de semestre et non null
                                //TODO voir le pretraitement si on zape ou pas la colonne
                                // si on cree une colone special changment de semestre  decomenté en dessous
                                if(/*$line[$colonne] != $semestre_suivant &&*/ $line[$colonne] != ""){
                                   // verif ue existe deja apres avoir trouvé le semestre
                                   $semestre_ue = jDao::createRecord('formations~semestre_ue');
                                    if($colonne < $colonne_semestre){
                                        $semestre_ue->id_semestre = $semestre1->id_semestre;
                                    }
                                    else{
                                        $semestre_ue->id_semestre = $semestre2->id_semestre;
                                    }
                                    if( !customSQL::ueExisteDeja(utf8_encode(strtoupper($line[$colonne])),$semestre_ue->id_semestre)){
                                        $ue = jDao::createRecord('ue~ue');
                                        $ue->code_ue = utf8_encode(strtoupper($line[$colonne]));
                                        $ue->credits = 1;
                                        $ue->coeff = 1;
                                        //$ue->libelle = $line[$colonne];
                                        $ue->annee = substr($formation->annee,0,4);
                                        $factoryUe->insert($ue);
                                        ///creation de l'ue et liaison au semestre en fonction du semestre correspondant
                                        
                                        $semestre_ue->id_ue = $ue->id_ue;
                                        $semestre_ue->optionelle = FAlSE;
                                        $factorySemestreUe->insert($semestre_ue);
                                        
                                    // on enregistre en fonction de la colonne l'id_ue
                                        $liste_ue[$colonne]["id_ue"] = $ue->id_ue;
                                    }
                                    // si l'ue existe deja
                                    else{
                                        $ue_semestre_ue = jDao::get('ue~ue_semestre_ue')->getByCodeSemestre(utf8_encode(strtoupper($line[$colonne])),$semestre_ue->id_semestre);
                                        
                                        $liste_ue[$colonne]["id_ue"] = $ue_semestre_ue->id_ue;

                                    }
                                }
                            }
                                else{
                                    $liste_ue[$colonne]["id_ue"] = $liste_ue[$colonne-1]["id_ue"];
                                }
                            //sinon l'ue est deja cree et elle estl a mm que dans la case precedente
                            
                            
                            $colonne++;
                        }
                        
                    break;
                
                    case 5 :
                    
                        $colonne = 6;
                        //boucle sur les colonne du fichier
                        while($colonne < $colonne_arret){
    
                            if($liste_ue[$colonne]["id_ue"] != -1){
                                //creation des epreuves
                                //si elle n'existe pas deja
                                if(!customSQL::epreuveExisteDeja($liste_ue[$colonne]["id_ue"],utf8_encode(strtoupper($line[$colonne])))){
                                    $epreuve = jDao::createRecord('ue~epreuve');
                                    $epreuve->id_ue = $liste_ue[$colonne]["id_ue"];
                                    $epreuve->coeff = 1;
                                    $epreuve->type_epreuve = utf8_encode(strtoupper($line[$colonne]));
                                    $factoryEpreuve->insert($epreuve);

                                }
                                
                                
                                //sinon on va la cherché dans la base
                                else{
                                    $epreuve = jDao::get('ue~epreuve')->getByUeAndType($liste_ue[$colonne]["id_ue"],utf8_encode(strtoupper($line[$colonne])));
                                }
                                //on enregistre chaque epreuve en fonction de la colonne
                                $liste_ue[$colonne]["id_epreuve"] = $epreuve->id_epreuve;
                            }
                            $colonne++;
                        }
                    break;
                
                    default :
                        if($line[2] != ""){
                        //si l'etudiant n'existe pas on le cree
                            if(!customSQL::etudiantsExisteDeja($line[2])){
                                $etudiant = jDao::createRecord('etudiants~etudiants');
                                $etudiant->num_etudiant = $line[2];
                                $etudiant->nom = utf8_encode(strtoupper($line[0]));
                                $etudiant->prenom = utf8_encode(ucfirst(strtolower($line[1])));
                                $etudiant->sexe = "?";
                                $factoryEtudiant->insert($etudiant);
                            }
                            else{
                                //sinon on le retrouve d'apres sa clef primaire
                               $etudiant = jDao::get('etudiants~etudiants')->get($line[2]);
                            }
                            //// ajout au 2 semestre par defaut
                            //TODO voir si ca pose des problemes
                            //si l'etudiant est deja inscrit au 2 semestres
                            if(!customSQL::etudiantSemestreExisteDeja($etudiant->num_etudiant,$semestre1->id_semestre)){
                                $etudiantSemestre1 = jDao::createRecord('etudiants~etudiants_semestre');
                                $etudiantSemestre1->num_etudiant = $etudiant->num_etudiant;
                                $etudiantSemestre1->id_semestre = $semestre1->id_semestre;
                                // on traite la descision du passage
                                $liste_decision = self::decision($line,$colonne_decision1,$colonne_arret);
                                $etudiantSemestre1->statut = $liste_decision;

                                $factoryEtudiantSemestre->insert($etudiantSemestre1);
                                ;
                            }
                            else{
                                
                            }
                            if(!customSQL::etudiantSemestreExisteDeja($etudiant->num_etudiant,$semestre2->id_semestre)){
                                $etudiantSemestre2 = jDao::createRecord('etudiants~etudiants_semestre');
                                $etudiantSemestre2->num_etudiant = $etudiant->num_etudiant;
                                $etudiantSemestre2->id_semestre = $semestre2->id_semestre;
                                // on traite la descision du passage
                                $liste_decision = self::decision($line,$colonne_decision2,$colonne_arret);
                                $etudiantSemestre2->statut = $liste_decision;
                                $factoryEtudiantSemestre->insert($etudiantSemestre2);
                            }
                            else{
                                
                            }
                            
                            //les notes commence a la case 7-1
                            //boucle sur les colonne du fichier
                            $colonne = 6;
                            while($colonne < $colonne_arret){
                                if($line[$colonne] != "" && $line[$colonne] != "S. Objet" && $liste_ue[$colonne]["id_ue"] != -1){
                                    $note = jDao::createRecord('ue~note');
                                    $note->id_epreuve = $liste_ue[$colonne]["id_epreuve"];
                                    //on assigne le semestre de la note en fonction du placement dans la colonne
                                    if($colonne < $colonne_semestre){
                                        $note->id_semestre = $semestre1->id_semestre;
                                    }
                                    else{
                                        $note->id_semestre = $semestre2->id_semestre;
                                    }
                                    if(!customSQL::noteExisteDeja($liste_ue[$colonne]["id_epreuve"],$note->id_semestre,$etudiant->num_etudiant)){
                                        $note->num_etudiant = $etudiant->num_etudiant;
                                        
                                        if($line[$colonne] === "abs"){
                                            $note->valeur = -1;
                                        }
                                        // on remplace la virgule par un point et on converti en float
                                        else{
                                            $note->valeur = floatval(str_replace(",",".",$line[$colonne]));
                                        }
                                        $factoryNote->insert($note);

                                        $nbajout++;
                                    }
                                }
                                $colonne++;
                            }
                        }
                    break;
                }
            }
            fclose($handle);
        }
        return $nbajout;
    }   
}