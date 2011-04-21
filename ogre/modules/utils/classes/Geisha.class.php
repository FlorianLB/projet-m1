<?php

class Geisha{

    public $fichier;
    
    function __construct($fichier){
        $this->fichier = $fichier;
    }
    
    public function codeFormation($delimiter = ";" ){
        
        $factoryFormation = jDao::get('/*formations~formation*/');
        
        if (($handle = fopen($this->fichier, "r")) !== FALSE) {
            //boucle sur les lignes du fichier
            while (($line = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                //si on est a la premiere ligne on lit la formation ainsi que l'année
                
                
                //si pas deja cree
                // on cree la formation clolone 0
                    //clolone 0 code formation
                    //clolone 2 nom
                    
                
                //sinon on l'ajoute l'ue la derniere entrer
                //s
                
               // cree ue
                //code colonne3
                //nom colonne4
    
            }   
        }
    }
}