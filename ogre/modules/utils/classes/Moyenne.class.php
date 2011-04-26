<?php

class Moyenne{
    
    public static function calcAllMoyenne($id_semestre,$num_etudiant){
        
        $ue_semestre_ue_factory = jDao::get('ue~ue_semestre_ue');
        $etudiants_semestre_factory = jDao::get('etudiants~etudiants_semestre');
        
        //Recuperation de etudiant semestre pour liste des options
        $etudiant_semestre = $etudiants_semestre_factory->get($num_etudiant,$id_semestre);
        
        $liste_ue = $ue_semestre_ue_factory->getBySemestre($id_semestre);
        
        $array_moyenne = array();
        
        foreach($liste_ue as $ue){
            if(!customSQL::DispenseValideExiste($id_semestre,$num_etudiant,$ue->id_ue)){
                //Verification si l'ue est optionnel
                if(customSQL::ueIsOptionelle($ue->id_ue,$id_semestre)){
                    //Si oui verifie que l'etudiant est inscrit
                    if(strstr($ue->code_ue,$etudiant_semestre->options) != FALSE){
                        //On calcule la moyenne
                        $array_moyenne[$ue->id_ue] = Moyenne::calcMoyenne($id_semestre,$num_etudiant,$ue->id_ue);
                    }
                }
                else{
                    //On calcule la moyenne
                    $array_moyenne[$ue->id_ue] = Moyenne::calcMoyenne($id_semestre,$num_etudiant,$ue->id_ue);
                }
            }
        }
        return $array_moyenne;
        
    }
    
    public static function calcMoyenne($id_semestre,$num_etudiant,$id_ue){
        
        jClasses::inc('utils~Formule');
        jClasses::inc('utils~EvalMath');
        //jClasses::inc('utils~customSQL');
        
        //TODO appliqué la bonne formule au note
        
        $epreuve_note_ue_factory = jDao::get('ue~epreuve_note_ue');
        $array_note = array();
        $ue_factory = jDao::get('ue~ue');
        $coeff;
        
       //Verifie si on doit appliqué la formule standard
        if(!customSQL::DispenseExiste($id_semestre,$num_etudiant,$id_ue)){
            $formule = $ue_factory->get($id_ue)->formule;
        }else{
            //$test=customSQL::DispenseType($id_semestre,$num_etudiant,$id_ue);
            //eval('$formule = $ue_factory->get($id_ue)->$test;');
            switch(customSQL::DispenseType($id_semestre,$num_etudiant,$id_ue)){
                case 'salarie' : $formule = $ue_factory->get($id_ue)->formule_salarie;
                case 'endette' : $formule = $ue_factory->get($id_ue)->formule_endette;
                case 'valide' : 
                default : return false;
                            break;
            }
        }
        //Parsage de la formule
        $formule_tmp=Formule::parseFormuleUeSup($formule);
        
        //on recupere les notes de l'ue
        $liste_note = $epreuve_note_ue_factory->getNoteBySemEtuUe($id_semestre,$num_etudiant,$id_ue);
        foreach($liste_note as $note){
            //On mets la valeur de chaque note dans un tableau si il y a dispense note = -1
            if(!customSQL::DispensePersoExiste($id_semestre,$num_etudiant,$note->id_epreuve)){
                $array_note[$note->type_epreuve] = $note->valeur;
            }
            else{
                $array_note[$note->type_epreuve] = -1;
            }
        }
        
        $formule_exp=explode(";", $formule);
        //Une fois l'affectation des notes terminé
        //On calcul les diviseur de chaque formule en gerant les dispense note = -1 si dispense A REMETTRE A 0 et soustraire le coeff au total
        $nb_formule=0;
        foreach($formule_tmp as $form){
            $coeff[$nb_formule]=0;
            //Pour chaque formule on parcours les coeff en verifiant que la note de ce coeff est != -1
            //si -1 on ajoute pas le coeff et on remet la note a 0 sinon on l'ajoute
            //TODO MODIFIER LE PARSAGE POUR AVOIR LES COEFF 1
            //On remplace la note au passage
            for($i=0;$i<sizeof($form[1]); $i++){
                if( $array_note[$form[2][$i]] == -1){
                    $array_note[$form[2][$i]]=0;
                }else{
                    $coeff[$nb_formule]=$coeff[$nb_formule]+$form[1][$i];
                }
                //On remplace dans la formule correspondante
                $formule_exp[$nb_formule]=str_replace($form[0][$i],$form[1][$i].'*'.$array_note[$form[2][$i]],$formule_exp[$nb_formule]);
            }
            //Puis on rajoute le diviseur a la fin de la formule
            $formule_exp[$nb_formule]='('.$formule_exp[$nb_formule].')/'.$coeff[$nb_formule];
            $nb_formule++;
        }
        //TODO Faire une boucle pour calculé les valeurs de chaque formule puis max ou alors le truc en dessous fonctionne
        //Sa marche ???
        $m = new EvalMath;
        for($i=0;$i<$nb_formule;$i++){
            $formule_exp[$i] = $m->e($formule_exp[$i]);
        }
        $moyenne=Max($formule_exp);
        
        return $moyenne;
    }
    
}