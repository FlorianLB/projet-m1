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
        
        $sql = 'SELECT 1 FROM etudiants WHERE num_etudiant = '.$cnx->quote($num_etudiant);
         
        $rs = $cnx->query($sql);
       
        if(!$rs->fetch())
            return false;
        else
            return true;
    }
    
    /**
     * Retourne vrai si l'ue existe deja dans la base
     */
    public static function ueExisteDeja($code_ue){
        $cnx = jDb::getConnection();
        
        $sql = 'SELECT 1 FROM ue WHERE code_ue = '.$cnx->quote($code_ue);
         
        $rs = $cnx->query($sql);
       
        if(!$rs->fetch())
            return false;
        else
            return true;
    }
    
    public static function epreuveExisteDeja($id_ue, $type_epreuve){
        $cnx = jDb::getConnection();
        
        $sql = 'SELECT 1 FROM epreuve WHERE id_ue = '.$cnx->quote($id_ue).' and type_epreuve = '.$cnx->quote($type_epreuve);
         
        $rs = $cnx->query($sql);
       
        if(!$rs->fetch())
            return false;
        else
            return true;
    }
    
    
    /**
     * Renvoit la liste des etudiants inscrit pour une epreuve et leur note si ils en ont deja une
     */
    public static function findEtudiantsNoteByEpreuveSemestre($id_epreuve, $id_semestre){
        $cnx = jDb::getConnection();
        
        //TODO a affiner si l'UE est une option, verifier que l'etudiant est inscrit dans cette option
        
        $sql = 'SELECT e.num_etudiant, e.nom, e.nom_usuel, e.prenom, n.valeur FROM
                    etudiants_semestre es
                    INNER JOIN etudiants e
                        ON e.num_etudiant = es.num_etudiant
                    LEFT OUTER JOIN (SELECT * FROM note tmp WHERE tmp.id_epreuve='.$cnx->quote($id_epreuve).' AND tmp.id_semestre = '.$cnx->quote($id_semestre).') n
                        ON n.num_etudiant = e.num_etudiant';

        
        $sql .= ' WHERE es.id_semestre = '.$cnx->quote($id_semestre);
        
        return $cnx->query($sql);
    }
    
    /**
     * Renvoit la liste des inscriptions completes d'un etudiant, semestre par semestre
     */
    public static function getAllInscriptionsEtudiant($num_etudiant){
        $cnx = jDb::getConnection();
        
        
        $sql = 'SELECT es.*, s.num_semestre, f.*
                    FROM etudiants_semestre es
                    INNER JOIN semestre s ON s.id_semestre = es.id_semestre
                    INNER JOIN formation f ON f.id_formation = s.id_formation
                    ';
        
        $sql .= ' WHERE es.num_etudiant = '.$cnx->quote($num_etudiant);
        
        $sql .= ' ORDER BY f.annee DESC';
        
        return $cnx->query($sql);
    }
}