<?php


class saisie_noteCtrl extends jController {

    private static $VALUE_ABS = -1;
    
    
    /**
    *
    */
    function save_saisie() {
        $rep = $this->getResponse('redirect');

        $num_etu = $this->param('num_etudiant');
        $notes = $this->param('note');
        
        $factory = jDao::get('ue~note');
        foreach($notes as $string => $note){

            
            $array = explode(':', $string);
            $id_epreuve = $array[0];
            $id_semestre = $array[1];
            
            
            if( $note == ''){
                $factory->delete($id_epreuve, $num_etu, $id_semestre);
               continue;
            }

            if( strcasecmp($note, 'ABS') == 0 ) {
                $valeur = self::$VALUE_ABS;
            }
            else if( floatval($note) >= 0 && floatval($note) <= 20) {
                $valeur = $note;
            }
            else
                continue;
            
            $old_note = $factory->get($id_epreuve, $num_etu, $id_semestre);
            
            // Si il n'y a pas encore de note
            if( !$old_note) {
                $record = jDao::createRecord('ue~note');
                $record->id_epreuve =  $id_epreuve;
                $record->id_semestre = $id_semestre;
                $record->num_etudiant = $num_etu;
                $record->valeur = $valeur;
                $factory->insert($record);
            }
            //MAJ de la note
            else{
                $old_note->valeur = $valeur;
                $factory->update($old_note);
            }
        }
        
        jMessage::add('Notes enregistrées !', 'confirm');
        
        $rep->action = 'etudiants~etudiants:view';
        $rep->params = array('id' =>$num_etu);
        
        return $rep;
    }
    
    
}
