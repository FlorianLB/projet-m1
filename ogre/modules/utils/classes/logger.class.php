<?php


class Logger {
    
    private static function _getUser(){
        return jAuth::getUserSession()->login;
    }
    
    
    public static function log($type, $param1 = null, $param2 = null, $param3 = null){
        
        
        $message = '';
        $file = 'default';
        
        switch($type) {
            
            case 'import_apogee' :
                $message = self::_importApogee($param1, $param2);
                $file = 'import';
            break;
        
            case 'creation_formation' :
                $message = self::_creationFormation($param1, $param2);
                $file = 'creation';
            break;  
            
        }
        
        if (!isset($GLOBALS['gJConfig']->logfiles[$file])) {
            return;
        }
        $f = $GLOBALS['gJConfig']->logfiles[$file];
        $sel = new jSelectorLog($f);
        
        error_log( date("d/m/Y H:i:s") . ' -- ' . $message ."\n", 3, $sel->getPath());
        
    }
    
    /**
     * @param string $filename Le fichier qui a été importé
     * @param int $nb_insertion Le nombre d'insertion effectuée
     */
    private static function _importApogee($filename, $nb_insertion){
        return self::_getUser().' a importé "'.$filename.'" ('.$nb_insertion." insertions d'etudiants)";
    }
    
    /**
     * @param string $formation_name Le nom de la formation qui a été créée
     * @param string $annee L'année de la formation
     */
    private static function _creationFormation($formation_name, $annee){
        return self::_getUser().' a créé la formation "'.$formation_name.'" pour l\'année '.$annee;
    }
    

}