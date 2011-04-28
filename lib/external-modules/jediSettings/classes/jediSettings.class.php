<?php
/**
* @package     jediSettings
* @author      Florian Lonqueu-Brochard
* @copyright   2011 Florian Lonqueu-Brochard
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

class jediSettings {
    
    public static $maxValueLength = 250;
    
    
    /**
     *
     * @param String $key
     */
    public static function get($key, $default = null, $profile = '') {
        
        $factory = jDao::get('jediSettings~jediSettings', $profile);
        
        $record = $factory->get($key);
        
        if (!$record && $default != null)
            return $default;
        elseif(!$record)
            return false;
        
        return $record->value;
    }
    
    /**
     *
     * @param String $key
     * @param String $value
     * @return int Return 1 if the setting have been created, 2 if it have been updated, FALSE if the param $value is too long
     */
    public static function set($key, $value, $profile = '') {
        
        if (!is_string($value))
            $value = serialize($value);
        
        if (strlen($value) > self::$maxValueLength)
            return false;
        
        $factory = jDao::get('jediSettings~jediSettings', $profile);
        
        $record = $factory->get($key);
        
        if (!$record) {
            $record = jDao::createRecord("jediSettings");
            $record->key = $key;
            $record->value = $value;
            $factory->insert($record);
            
            return 1;
        }
        else {
            $record->value = $value;
            $factory->update($record);
            
            return 2;
        }
    }
    
    /**
     *
     * @param String $key
     */
    public static function delete($key, $profile = '') {
        
        $factory = jDao::get('jediSettings~jediSettings', $profile);
        
        return $factory->delete($key);
    }
    
    
}