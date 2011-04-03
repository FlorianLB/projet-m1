<?php

class Formule{
    
    public static function parseFormuleUe($formule){
        
        if(!is_string($formule))
            return;
        
        $result = array();
        preg_match_all('/([0-9])?[ *]*([a-zA-Z]+[0-9]?)/',$formule, $result);
        
        return $result;
    }
    
    
}





