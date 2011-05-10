<?php

class NoteImport{
    
    /**
     * Exporte toute les notes (par epreuve) d'une ue vers la meme ue plus recente ou relié a la nouvelle année de l'etudiant
     * 
     * @param int $id_semestre  id du semestre des notes a exporté
     * @param int $num_etudiant num de l'etudiant des notes a exporté
     * @param int $id_ue        id de l'ue des notes a exporté
     * 
     * 
     */    
    public static function importAllNotes($id_semestre,$num_etudiant,$id_ue){
        $epreuve_note_ue_factory = jDao::get('ue~epreuve_note_ue');
        $liste_note = $epreuve_note_ue_factory->getNoteByEtuUeSem($id_semestre,$num_etudiant,$id_ue);
        foreach($liste_note as $note){
            importEpreuveNote($id_semestre,$num_etudiant,$id_ue,$note->epreuve);
        }
    }

    /**
     * Exporte la note d'une epreuve d'un etudiant donnée pour un semestre et une ue vers
     * la meme ue dans la nouvelle formation de l'etudiant
     * 
     * @param int $id_semestre  id du semestre des notes a exporté
     * @param int $num_etudiant num de l'etudiant des notes a exporté
     * @param int $id_ue        id de l'ue des notes a exporté
     * @param int $id_epreuve   id de l'epreuve dont on veut exporté la note
     * 
     * 
     */
    public static function importEpreuveNote($id_semestre,$num_etudiant,$id_ue,$id_epreuve){
        $note_factory = jDao::get('ue~note');
        $ue_factory = jDao::get('ue~ue');
        $epreuve_factory = jDao::get('ue~epreuve');
        
        $etudiantsemsetre_factory = jDao::get('etudiants~etudiants_semestre');
        $semestreue_factory = jDao::get('ue~semestre_ue');
        
        //Recuperation des anciennes valeurs
        $old_ue = $ue_factory->get($id_ue);
        $old_epreuve = $epreuve_factory->get($id_epreuve);
        $old_note = $note_factory->get($id_epreuve,$num_etudiant,$id_semestre);
        
        //Recuperation des nouvelles valeurs
        $new_ue=$ue_factory->getLastUeByCode($old_ue->code_ue);
        $new_epreuve = $epreuve_factory->getByUeAndType($new_ue->id_ue,$old_epreuve->type_epreuve);
        //TODO Trouve le nouveau semestre A TESTER
        $listesemestre_etudiant = $etudiantsemsetre_factory->getByEtudiant($num_etudiant);
        foreach($listesemestre_etudiant as $etusemestre){
            if( $etusemestre->statut=='DET' || $etusemestre->statut=='ENC' ){
                $new_semestre = $semestreue_factory->get($new_ue->id_ue,$etusemestre->id_semestre);
                if($new_semestre != null){
                    break;
                }
            }
        }
        
        //Si l'epreuve existe
        if($new_epreuve != NULL){
            //On la crée avec le statut a 1 pour note importé
            $new_note = jDao::createRecord('ue~note');
            $new_note->id_epreuve=$new_epreuve->id_epreuve;
            $new_note->num_etudiant=$num_etudiant;
            //TODO Recuperer l'id du semestre de la nouvelle ue
            $new_note->id_semestre=$new_semestre->id_semestre;
            $new_note->valeur=$old_note->valeur;
            $new_note->statut=1;
            $note_factory->insert($new_note);
        }else{
            //TODO Remplir la table probleme
        }
        
    }
    
}