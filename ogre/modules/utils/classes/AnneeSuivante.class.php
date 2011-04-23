<?php

jClasses::inc('utils~customSQL');

class AnneeSuivante{
    
    function creationAnnee(){
    
        $annee = customSQL::findDerniereAnnee();
        Jlog::log("année");
        jLog::dump($annee);
        //liste des derniere formation
        $formationList = jDao::get('formations~formation')->getByAnnee($annee);
        
        //changement de la date
        //on tronc les 5 premier caractere
        $annee = substr($annee,5,4);
        //on y ajoute "-année"
        $annee .= "-".($annee + 1);
        Jlog::log("année2");
        jLog::dump($annee);
        
        
        $factoryFormation = jDao::get('formations~formation');
        $factorySemestre = jDao::get('formations~semestre');
        $factorySemestre_ue = jDao::get('formations~semestre_ue');
        $factoryCompensation = jDao::get('formations~compensation_semestre');
        
        //pour chaque formation
        foreach($formationList as $oldFormation){
            //cree une nouvelle
            $formation = jDao::createRecord('formations~formation');
            $formation->code_formation = $oldFormation->code_formation;
            $formation->libelle = $oldFormation->libelle;
            $formation->annee = $annee;
            $factoryFormation->insert($formation);
            
            
            
            //on cree les semestres et on les attache
            $semestre1 = jDao::createRecord('formations~semestre');
            $semestre1->id_formation = $formation->id_formation;
            $semestre1->num_semestre = 1;
            $semestre2 = jDao::createRecord('formations~semestre');
            $semestre2->id_formation = $formation->id_formation;
            $semestre2->num_semestre = 2;
            $factorySemestre->insert($semestre1);
            $factorySemestre->insert($semestre2);
            
            // compensation semestre
            $compensation = jDao::createRecord('formations~compensation_semestre');
            $compensation->id_semestre1 = $semestre1->id_semestre;
            $compensation->id_semestre2 = $semestre2->id_semestre;
            $factoryCompensation->insert($compensation);
            
            //on recupere la liste des ue des ancien semestre
            // et on atache chaque ue au semestre 1 et 2
            $oldSemestre1 = jDao::get('formations~semestre')->getByFormationNum($oldFormation->id_formation,1);
            $ueList = jDao::get('ue~ue_semestre_ue')->getBySemestre($oldSemestre1->id_semestre);
            
            foreach($ueList as $oldUe){
                $ue = jDao::get('ue~ue')->getLastUeByCode($oldUe->code_ue);
                $semestre_ue = jDao::createRecord('ue~ue_semestre_ue');
                if(customSQL::ueIsOptionelle($ue->id_ue,$oldSemestre1->id_semestre) == TRUE){
                    $semestre_ue->optionelle = TRUE;                    
                }
                else{
                    $semestre_ue->optionelle = FALSE;
                }
                $semestre_ue->id_ue = $ue->id_ue;
                $semestre_ue->id_semestre = $ue->id_semestre;
                $factorySemestre_ue->insert($semestre_ue);
                
            }
            
            //semestre2
            $oldSemestre = jDao::get('formations~semestre')->getByFormationNum($oldFormation->id_formation,2);
            $ueList = jDao::get('ue~ue_semestre_ue')->getBySemestre($semestre2->id_semestre);
            
            foreach($ueList as $oldUe){
                $ue = jDao::get('ue~ue')->getLastUeByCode($oldUe->code_ue);
                $semestre_ue = jDao::createRecord('ue~ue_semestre_ue');
                if(customSQL::ueIsOptionelle($ue->id_ue,$oldSemestre->id_semestre) == TRUE){
                    $semestre_ue->optionelle = TRUE;                    
                }
                else{
                    $semestre_ue->optionelle = FALSE;
                }
                $semestre_ue->id_ue = $ue->id_ue;
                $semestre_ue->id_semestre = $ue->id_semestre;
                $factorySemestre_ue->insert($semestre_ue);
                
            }        
        }
    }  
}