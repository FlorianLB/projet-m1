<?php

class Formule{
    
    //TODO les formules contenant des sup devront etre separer par des ;
    
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
    
    //Permet de recup chaque formule separement en cas de sup peux remplacé l'autre ???
    public static function parseFormuleUeSup($formule){
        
        if(!is_string($formule))
            return;
        
        $result = array();
        $formule_exp=explode(";", $formule);
        
        //TODO verifier si sa marche
        foreach($formule_exp as $form_exp){
            preg_match_all('/([0-9])?[ *]*([a-zA-Z]+[0-9]?)/',$form_exp, $result[]);
        }
        
        foreach($result as $res){
            foreach($res[1] as &$row){
                if(empty($row)){
                    $row = "1";
                }
            }
        }
        
/*  Commentez car supposé que les SUP ne sont pas écrit dans la formule
      $i = 0;
        foreach($result[2] as $row1){
            if(strtolower($row1) == "sup"){
                unset($result[0][$i]);
                unset($result[1][$i]);
                unset($result[2][$i]);
            }
            $i++;
        }
        
*/
        return $result;
    }
    
    
}





