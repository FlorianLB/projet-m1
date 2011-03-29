<?php

class anneeData implements jIFormsDatasource{
    
    protected $formId = 0;
    
    protected $data = array();
    
    function __construct($id){
        $this->formId = $id;
        
        
        $actual_month = intval(date('m'));
        $annee1 = $annee2 = intval(date('Y'));
        
        if($actual_month > 7 )
            $annee2++;
        else
            $annee1--;
            
        $s = $sbis = $annee1.'-'.$annee2;
        $this->data[$sbis] = $sbis;
        
        $annee1bis = $annee1;
        $annee2bis = $annee2;
         
        for($i=0; $i<2; $i++) {
            $s = $annee1--.'-'.$annee2--;
            $this->data[$s] = $s;
            
            $s = $annee1bis++.'-'.$annee2bis++;
            $this->data[$s] = $s;
        }
        //ksort($this->data);
        
    }
    
    public function getData($form){
        return ($this->data);
    }
    
    public function getLabel($key) {
        if(isset($this->data[$key]))
            return $this->data[$key];
        else
            return null;
    }  
    
}