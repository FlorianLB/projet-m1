<?php

class NoteImport{
    
    public static function importAllNotes($id_semestre,$num_etudiant,$id_ue){
        $epreuve_note_ue_factory = jDao::get('ue~epreuve_note_ue');
        $liste_note = $epreuve_note_ue_factory->getNoteByEtuUeSem($id_semestre,$num_etudiant,$id_ue);
        foreach($liste_note as $note){
            importEpreuveNote($id_semestre,$num_etudiant,$id_ue,$note->epreuve);
        }
    }
    
    public static function importEpreuveNote($id_semestre,$num_etudiant,$id_ue,$id_epreuve){
        $note_factory = jDao::get('ue~note');
        $ue_factory = jDao::get('ue~ue');
        $epreuve_factory = jDao::get('ue~epreuve');
        
        //Recuperation des anciennes valeurs
        $old_ue = $ue_factory->get($id_ue);
        $old_epreuve = $epreuve_factory->get($id_epreuve);
        $old_note = $note_factory->get($id_epreuve,$num_etudiant,$id_semestre);
        
        //Recuperation des nouvelles valeurs
        $new_ue=$ue_factory->getLastUeByCode($old_ue->code_ue);
        $new_epreuve = $epreuve_factory->getByUeAndType($new_ue->id_ue,$old_epreuve->type_epreuve);
        
        //Si l'epreuve existe
        if($new_epreuve != NULL){
            //On la crée avec le statut a 1 pour note importé
            $new_note = jDao::createRecord('ue~note');
            $new_note->id_epreuve=$new_epreuve->id_epreuve;
            $new_note->num_etudiant=$num_etudiant;
            //TODO Recuperer l'id du semestre de la nouvelle ue
            //$new_note->id_semestre=;
            $new_note->valeur=$old_note->valeur;
            $new_note->statut=1;
            $note_factory->insert($new_note);
        }
        
    }
    
}