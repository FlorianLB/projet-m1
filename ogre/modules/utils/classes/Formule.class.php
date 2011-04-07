<?php

class Formule{
    
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
        

        return $result;
    }
    
    
}





