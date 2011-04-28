<?php

jClasses::inc('utils~customSQL');

class Geisha{

    public $fichier;
    
    function __construct($fichier){
        $this->fichier = $fichier;
    }
    
    public function codeFormation($delimiter = ";", $annee ){
        
        $factoryFormation = jDao::get('formations~formation');
        
        $factoryFormation = jDao::get('formations~formation');
        $factorySemestre = jDao::get('formations~semestre');
        $factorySemestre_ue = jDao::get('formations~semestre_ue');
        $factoryCompensation = jDao::get('formations~compensation_semestre');
        $factoryUe = jDao::get('ue~ue');
        $derniereFormation = "";
        
        if (($handle = fopen($this->fichier, "r")) !== FALSE) {
            //boucle sur les lignes du fichier
            while (($line = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                // pas de semestre sur les ue geisha donc creation simple de ue ...
                // TODO verification existace formation
                //si c'est une nouvelle formation sur la ligne on la cree'
                //if($derniereFormation != $line[0]){
                //    $derniereFormation = $line[0];
                //    $formation = jDao::createRecord('formations~formation');
                //    $formation->code_formation = $line[0];
                //    $formation->annee = $annee;
                //    $formation->libelle = $line[2];
                //    $factoryFormation->insert($formation);
                //    // creation des semestre correspondant
                //    $semestre1 = jDao::createRecord('formations~semestre');
                //    $semestre1->id_formation = $formation->id_formation;
                //    $semestre1->num_semestre = 1;
                //    
                //    //On créer le 2eme
                //    $semestre2 = jDao::createRecord('formations~semestre');
                //    $semestre2->id_formation = $formation->id_formation;
                //    $semestre2->num_semestre = 2;
                //    
                //    // On insère via la factory DAO
                //    $factorySemestre = jDao::get('formations~semestre');
                //    $factorySemestre->insert($semestre1);
                //    $factorySemestre->insert($semestre2);
                //    
                //    $compensation = jDao::createRecord('formations~compensation_semestre');
                //    $compensation->id_semestre1 = $semestre1->id_semestre;
                //    $compensation->id_semestre2 = $semestre2->id_semestre;
                //    $factoryCompensation->insert($compensation);
                //}
                $ue = jDao::createRecord('ue~ue');
                $ue->code_ue = utf8_encode(strtoupper($line[3]));
                $ue->libelle = utf8_encode($line[4]);
                $ue->credits = 1;
                $ue->coeff = 1;
                //$ue->libelle = $line[$colonne];
                $ue->annee = substr($annee,0,4);
                $factoryUe->insert($ue); 
            }   
        }
    }
}