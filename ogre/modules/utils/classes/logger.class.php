<?php


class Logger {
    
    public static function log($type, $param1 = null, $param2 = null, $param3 = null){
        
        
        $message = '';
        $file = 'default';
        
        switch($type) {
            
            case 'import_apogee' :
                $message = self::_importApogee($param1, $param2);
                $file = 'import';
            break;  
            
        }
        
        if (!isset($GLOBALS['gJConfig']->logfiles[$file])) {
            return;
        }
        $f = $GLOBALS['gJConfig']->logfiles[$file];
        $sel = new jSelectorLog($f);
        error_log( $message, 3, $sel->getPath);
        
    }
    
    
    private static function _importApogee($filename, $nb_insertion){
        return self::getUser().' a importé "'.$filename.'" ('.$n_insertion." insertions d'etudiants)";
    }
    
    private static function _getUser(){
        return '';
    }
    
    private static function _getDateTime(){
        return '';
    }
    
}