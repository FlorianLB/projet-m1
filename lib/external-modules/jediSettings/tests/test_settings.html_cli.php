<?php

class testSettings extends UnitTestCase {
    
    function testCRUD() {
        
        jClasses::inc('jediSettings~jediSettings');
        
        $actual_key = 'unittest-'.time();
        $actual_key_bis = $actual_key.'_bis';
        $dummy_value = 'jelix';
        $dummy_value_bis = $dummy_value.'_bis';
        
        $maxValueLength = jediSettings::$maxValueLength;
        $very_long_value = '';
        for ($i = 0; $i < $maxValueLength + 1; $i++) {
            $very_long_value .= 'o';
        }
        $dummy_array = array('lol' => 'jelix', 'id' => 25);
        
        
        //initial
        $this->assertFalse(jediSettings::get($actual_key));
        
        //insertion
        $this->assertEqual(jediSettings::set($actual_key, $dummy_value), 1);
        $this->assertEqual(jediSettings::set($actual_key_bis, $dummy_value), 1);
        
        //get
        $this->assertEqual(jediSettings::get($actual_key), $dummy_value);
        $this->assertEqual(jediSettings::get($actual_key_bis), $dummy_value);
        
        //modification
        $this->assertEqual(jediSettings::set($actual_key, $dummy_value_bis ), 2);
    
        
        //length verification
        $this->assertFalse(jediSettings::set($actual_key, $very_long_value));
        
        //serialize verification
        jediSettings::set($actual_key, $dummy_array);
        $this->assertEqual(jediSettings::get($actual_key), serialize($dummy_array));
        
        
        //suppression
        jediSettings::delete($actual_key);
        $this->assertFalse(jediSettings::get($actual_key));
        
        jediSettings::delete($actual_key_bis);
        $this->assertFalse(jediSettings::get($actual_key_bis));
    }
    
}
