<?php

class customSQL{
    
    
    
    public static function findAllDistinctCodeFormation(){
        $cnx = jDb::getConnection();
        
        $sql = 'SELECT DISTINCT code_formation FROM formation';
         
        return $cnx->query($sql);
    }
    
    
    /**
     * Retourne vrai si l'etudiant existe deja dans la base
     */
    public static function etudiantsExisteDeja($num_etudiant){
        $cnx = jDb::getConnection();
        
        $sql = 'SELECT num_etudiant FROM etudiants WHERE num_etudiant = '.$cnx->quote($num_etudiant);
         
        $rs = $cnx->query($sql);
       
        if(!$rs->fetch())
            return false;
        else
            return true;
    }
    
}