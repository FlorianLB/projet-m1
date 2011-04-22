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
     * Retourne vrai si l'etudiant a une dispence pour l'ue de ce semestre TODO Voir si peut retourné la dispence
     */
    public static function DispenseExiste($id_semestre,$num_etudiant,$id_ue){
        $cnx = jDb::getConnection();
        
        $sql = 'SELECT 1 FROM dispense WHERE num_etudiant = '.$cnx->quote($num_etudiant).
                ' and id_semestre = '.$cnx->quote($id_semestre).
                ' and id_ue = '.$cnx->quote($id_ue);
         
        $rs = $cnx->query($sql);
       
        if(!$rs->fetch())
            return false;
        else
            return true;
    }
    
    public static function DispensePersoExiste($id_semestre,$num_etudiant,$id_epreuve){
        $cnx = jDb::getConnection();
        
        $sql = 'SELECT 1 FROM dispense WHERE num_etudiant = '.$cnx->quote($num_etudiant).
                ' and id_semestre = '.$cnx->quote($id_semestre).
                ' and id_epreuve = '.$cnx->quote($id_epreuve);
         
        $rs = $cnx->query($sql);
       
        if(!$rs->fetch())
            return false;
        else
            return true;
    }
    
    /**
     * Retourne vrai si l'ue existe deja dans la base
     */
    public static function ueExisteDeja($code_ue,$id_semestre){
        $cnx = jDb::getConnection();

        $sql = 'SELECT 1 FROM ue
                        INNER JOIN semestre_ue s ON ue.id_ue = s.id_ue
                        WHERE ue.code_ue = '.$cnx->quote($code_ue).
                        ' and s.id_semestre = '.$cnx->quote($id_semestre);
         
        $rs = $cnx->query($sql);
       
        if(!$rs->fetch())
            return false;
        else
            return true;
    }
    
    public static function noteExisteDeja($id_epreuve,$id_semestre,$num_etudiant){
        $cnx = jDb::getConnection();

        $sql = 'SELECT 1 FROM note
                        WHERE id_epreuve = '.$cnx->quote($id_epreuve).
                        ' and id_semestre = '.$cnx->quote($id_semestre).
                        ' and num_etudiant = '.$cnx->quote($num_etudiant);
         
        $rs = $cnx->query($sql);
       
        if(!$rs->fetch())
            return false;
        else
            return true;
    }
    
    public static function ueIsOptionnel($id_ue,$id_semestre){
        $cnx = jDb::getConnection();

        $sql = 'SELECT optionnel FROM semestre_ue WHERE id_ue = '.$cnx->quote($id_ue).
                                    ' and id_semestre = '.$cnx->quote($id_semestre);
                                    
        return $cnx->query($sql);

    }
    
    public static function formationExisteDeja($code_formation, $annee){
        $cnx = jDb::getConnection();
        
        $sql = 'SELECT 1 FROM formation WHERE code_formation = '.$cnx->quote($code_formation).
                                    ' and annee = '.$cnx->quote($annee);
         
        $rs = $cnx->query($sql);
       
        if(!$rs->fetch())
            return false;
        else
            return true;
    }
    
    
        public static function etudiantSemestreExisteDeja($num_etudiant, $id_semestre){
        $cnx = jDb::getConnection();
        
        $sql = 'SELECT 1 FROM etudiants_semestre WHERE num_etudiant = '.$cnx->quote($num_etudiant).
                                    ' and id_semestre = '.$cnx->quote($id_semestre);
         
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
        
        
        $sql = 'SELECT es.*, s.num_semestre, f.* , ss.*
                    FROM etudiants_semestre es
                    INNER JOIN semestre s ON s.id_semestre = es.id_semestre
                    INNER JOIN formation f ON f.id_formation = s.id_formation
                    INNER JOIN statut_semestre ss ON es.statut = ss.statut
                    ';
        
        $sql .= ' WHERE es.num_etudiant = '.$cnx->quote($num_etudiant);
        
        $sql .= ' ORDER BY f.annee DESC';
        
        return $cnx->query($sql);
    }
    
    
    
    /**
     * Renvoit la liste des etudiants inscrit pour une epreuve et leur note si ils en ont deja une
     */
    public static function findRegularNoteByEtudiant($num_etudiant){
        $cnx = jDb::getConnection();
        
        //TODO a affiner si l'UE est une option, verifier que l'etudiant est inscrit dans cette option
        
        $sql = 'SELECT DISTINCT es.*, n.valeur, n.statut as n_statut, ev.*, s.num_semestre, ue.code_ue, ue.libelle as ue_libelle, f.annee, f.code_formation, dp.id_epreuve as flag_dispense FROM
                    etudiants_semestre es
                    INNER JOIN semestre s
                        ON s.id_semestre = es.id_semestre
                    INNER JOIN formation f
                        ON f.id_formation = s.id_formation
                    INNER JOIN semestre_ue se
                        ON se.id_semestre = s.id_semestre
                    INNER JOIN ue
                        ON ue.id_ue = se.id_ue
                    INNER JOIN epreuve ev
                        ON ev.id_ue = ue.id_ue
                    
                    LEFT OUTER JOIN note n
                        ON n.id_epreuve = ev.id_epreuve AND es.num_etudiant = n.num_etudiant AND n.id_semestre = es.id_semestre
                    
                    LEFT OUTER JOIN dispense_perso dp
                        ON dp.id_epreuve = ev.id_epreuve AND es.num_etudiant = dp.num_etudiant AND dp.id_semestre = es.id_semestre
                    ';

        
        $sql .= ' WHERE es.num_etudiant = '.$cnx->quote($num_etudiant)." AND (es.statut = 'ENC' OR es.statut = 'DET')";
        
        $sql .= ' ORDER BY es.id_semestre ASC, ue.code_ue ASC';
        
        return $cnx->query($sql);
    }
    
    
    
    
    
    public static function findDispenseByEtudiant($num_etudiant){
        $cnx = jDb::getConnection();
        
        //TODO a affiner si l'UE est une option, verifier que l'etudiant est inscrit dans cette option
        
        $sql = 'SELECT DISTINCT es.*, ev.*, s.num_semestre, ue.code_ue, ue.libelle as ue_libelle, f.annee, f.code_formation, dp.num_etudiant as flag_dispense FROM
                    etudiants_semestre es
                    INNER JOIN semestre s
                        ON s.id_semestre = es.id_semestre
                    INNER JOIN formation f
                        ON f.id_formation = s.id_formation
                    INNER JOIN semestre_ue se
                        ON se.id_semestre = s.id_semestre
                    INNER JOIN ue
                        ON ue.id_ue = se.id_ue
                    INNER JOIN epreuve ev
                        ON ev.id_ue = ue.id_ue
                    
                    LEFT OUTER JOIN dispense_perso dp
                        ON dp.id_epreuve = ev.id_epreuve AND es.num_etudiant = dp.num_etudiant AND dp.id_semestre = es.id_semestre
                    
                    ';

        
        $sql .= ' WHERE es.num_etudiant = '.$cnx->quote($num_etudiant)." AND (es.statut = 'ENC' OR es.statut = 'DET')";
        
        $sql .= ' ORDER BY es.id_semestre ASC, ue.code_ue ASC';
        
        return $cnx->query($sql);
    }



    public static function findDispensePredefByEtudiant($num_etudiant){
        $cnx = jDb::getConnection();
        
        //TODO a affiner si l'UE est une option, verifier que l'etudiant est inscrit dans cette option
        
        $sql = 'SELECT DISTINCT es.*, s.num_semestre, ue.id_ue, ue.code_ue, ue.libelle as ue_libelle, f.annee, f.code_formation, dp.salarie, dp.endette, dp.commentaire FROM
                    etudiants_semestre es
                    INNER JOIN semestre s
                        ON s.id_semestre = es.id_semestre
                    INNER JOIN formation f
                        ON f.id_formation = s.id_formation
                    INNER JOIN semestre_ue se
                        ON se.id_semestre = s.id_semestre
                    INNER JOIN ue
                        ON ue.id_ue = se.id_ue
                    
                    LEFT OUTER JOIN dispense dp
                        ON dp.id_ue = ue.id_ue AND es.num_etudiant = dp.num_etudiant AND dp.id_semestre = es.id_semestre
                    ';
        $sql .= ' WHERE es.num_etudiant = '.$cnx->quote($num_etudiant)." AND (es.statut = 'ENC' OR es.statut = 'DET')";
        $sql .= ' ORDER BY es.id_semestre ASC, ue.code_ue ASC';
        
        return $cnx->query($sql);
    }
    
    
     public static function finDerniereAnnee($annee){
        $cnx = jDb::getConnection();
    
        $sql = 'SELECT MAX('.$cnx->quote($annee).") FROM formation"
        return $cnx->query($sql);
    
}