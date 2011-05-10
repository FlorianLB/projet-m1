<?php

class Formule{
    
    //Les formules contenant des sup devront etre separer par des ;
    
    /**
     * Parsage de la formule
     * 
     * @param String $formule Formule a parse
     * 
     * @return Array    Formule parse de la façon suivante [0]:coeff+epreuve,[1]:coeff,[2]:epreuve
     */
    public static function parseFormuleUe($formule){
        
        if(!is_string($formule))
            return;
        
        $result = array();
        preg_match_all('/([0-9])?[ *]*([a-zA-Z]+[0-9]?)/',$formule, $result);
        
     $i = 0;
        foreach($result[2] as $row1){
            if(strtolower($row1) == "sup"){
                unset($result[0][$i]);
                unset($result[1][$i]);
                unset($result[2][$i]);
            }
            $i++;
        }
        foreach($result[1] as &$row){
            if(empty($row)){
                $row = "1";
            }
        }

        return $result;
    }
    
    /**
     * Parsage de la formule 
     * 
     * @param String $formule Formule a parse
     * 
     * @return Array[]    Tableau des Formules parse de la façon suivante [0]:coeff+epreuve,[1]:coeff,[2]:epreuve
     *                      avec un tableau pour chaque formule du SUP
     */
    public static function parseFormuleUeSup($formule){
        
        if(!is_string($formule))
            return;
        
        $result = array();
        $formule_exp=explode(";", $formule);
        
        foreach($formule_exp as $form_exp){
            preg_match_all('/([0-9])?[ *]*([a-zA-Z]+[0-9]?)/',$form_exp, $result[]);
        }
        
        foreach($result as &$res){
            foreach($res[1] as &$row){
                if(empty($row)){
                    $row = "1";
                }
            }
        }
        
        return $result;
    }
    
    
}





