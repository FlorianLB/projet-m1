<?php

jClasses::inc('utils~customSQL');

class etudiantsApogee{
    
    public $fichier;
    
    //Tableau comprenant toutes les formations gérées par le secretariat
    public $formations = array();
    
    
    function __construct($fichier){
        $this->fichier = $fichier;
        
        foreach(customSQL::findAllDistinctCodeFormation() as $f){
            $this->formations[] = $f->code_formation;
        }

    }
    
    /**
     * Parse le fichier CSV Apogée
     *
     * @param boolean $only_identite Extraction faites uniquement sur l'identite de l'etudiant
     */
    public function parse($only_identite = true, $delimiter = ";" ){
        
        $data = array();
        
        if (($handle = fopen($this->fichier, "r")) !== FALSE) {
            while (($line = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                
                
                if(in_array($line[0], $this->formations)){
    
                    if($only_identite) {
                        $etu = new stdClass;
                        $etu->formation = $line[0];
                        $etu->formation_label = $line[2];
                        $etu->num_etudiant = $line[3];
                        $etu->sexe = $line[4];
                        $etu->nom = $line[5];
                        $etu->prenom = $line[6];
                        $etu->nom_usuel = $line[7];
                        $etu->date_naissance = $line[8];
                        $etu->adresse = $line[11];
                        $etu->code_postal = $line[12];
                        $etu->ville = $line[13];
                        $etu->telephone = $line[14];
                        $etu->email= $line[30];
                        
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