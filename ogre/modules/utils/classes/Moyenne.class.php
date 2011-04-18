<?php

class Moyenne{
    
    public function caclAllMoyenne($id_semestre,$num_etudiant){
        
        $ue_semestre_ue_factory = jDao::get('ue~ue_semestre_ue');
        $etudiants_semestre_factory = jDao::get('etudiants~etudiants_semestre');
        
        //Recuperation de etudiant semestre pour liste des options
        $etudiant_semestre = $etudiants_semestre_factory->get($num_etudiant,$id_semestre);
        
        $liste_ue = $ue_semestre_ue_factory->getBySemestre($id_semestre);
        
        $array_moyenne = array();
        
        foreach($liste_ue as $ue){
            //Verification si l'ue est optionnel
            if(customSQL::ueIsOptionnel($ue->id_ue,$id_semestre)){
                //Si oui verifie que l'etudiant est inscrit
                if(strstr($ue->code_ue,$etudiant_semestre->options) != FALSE){
                    //On calcule la moyenne
                    $array_moyenne[$id_ue] = caclMoyenne($id_semestre,$num_etudiant,$id_ue);
                }
            }
            //TODO Verifier l'existence d'une dispense a l'ue (pas formule differente)
            // customSQL::DispenseExiste($id_semestre,$num_etudiant,$id_ue); ???
            else{
                //On calcule la moyenne
                $array_moyenne[$id_ue] = caclMoyenne($id_semestre,$num_etudiant,$id_ue);
            }
        }
        
    }
    
    public function caclMoyenne($id_semestre,$num_etudiant,$id_ue){
        
         //TODO appliqué la bonne formule au note
        
        $epreuve_note_ue_factory = jDao::get('ue~epreuve_note_ue');
        $array_note = array();
        
       //Verifie si on doit appliqué la formule standard
        if(!customSQL::DispenseExiste($id_semestre,$num_etudiant,$id_ue)){
            //Si oui on recupere les notes de l'ue
            $liste_note = $epreuve_note_ue_factory->getNoteByEtuUeSem($id_semestre,$num_etudiant,$id_ue);
            foreach($liste_note as $note){
                //On mets la valeur de chaque note dans un tableau
                //TODO pas utile mais peux facilité le calcul apres
                $array_note['$note->type_epreuve'] = $note->valeur;
            }
            //TODO Trouver un moyen simple d'affecté la note a la formule
            //$moyenne=
        }else{
            
        }
        
    }
    
}