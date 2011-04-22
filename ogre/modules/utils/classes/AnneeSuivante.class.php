<?php

jClasses::inc('utils~customSQL');

class AnneeSuivante(){
    
    function creationAnnee(){
    
        $annee = customSQL::finDerniereAnnee();
        //liste des derniere formation
        $formationList = jDao::get('formations~formation')->getByAnnee($annee);
        
        //changement de la date
        $annee = substr($annee,5,4);
        $annee .= "-".($annee + 1);
        
        
        $factoryFormation = jDao::get('formations~formation');
        $ueList = jDao::get('formations~formation');
        
        //pour chaque formation
        foreach($formationList as $formation){
            //cree une nouvelle
            $formation->id_formation = null;
            $formation->annee = $annee;
            $factoryFormation->insert($formation);
            
            
            //regard les semestre...
            // compensation semestre
            //semestre_ue
            
            
            //on regard les derniere ue avec le codes des ancienne ue de la formation
            //foreach ue
                //on cree les ue dans la new formation
                
                //puis on recree les les epreuves
                
                
                jClasses::inc('utils~Formule');
                $formules = array();
                $formules[0] = Formule::parseFormuleUe($form->getData('formule'));
                $formules[1] = Formule::parseFormuleUe($form->getData('formule2'));
                $formules[2] = Formule::parseFormuleUe($form->getData('formule_salarie'));
                //initialisation des dao
                $factory = jDao::get('ue~epreuve');
                $epreuve = jDao::createRecord('ue~epreuve');
               
                // pour chaques formules on cree les epreuves
        
                foreach($formules as $formule){
                 //pour chaqun des element de la formule on crée une epreuve
                    foreach($formule[2] as $epreuve_temp){
                    //if(strtolower($var[$j][2][$i]) != "sup"){
                        if(!customSQL::epreuveExisteDeja($id,$epreuve_temp)){
                            $epreuve = jDao::createRecord('ue~epreuve');
                            $epreuve->id_ue = $id;
                            $epreuve->coeff = 1;
                            $epreuve->type_epreuve = $epreuve_temp;
                            $factory = jDao::get('ue~epreuve');
                            $factory->insert($epreuve);
                        }
                    }
                }
        
        }
        
        
        
    }
    
    
    
}