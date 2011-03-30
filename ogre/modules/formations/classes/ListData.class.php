<?php

class ListData implements jIFormsDatasource{
  protected $formId = 0;
 
  protected $data = array();
 
  function __construct($id){
    $this->formId = $id;
    $this->data = array();
    $factoryformation = jDao::get('formations~formation');
    
    $formationliste = $factoryformation->getAllCode();
    
    foreach($formationliste as $formation){
      $idform = $factoryformation->getLastFormationByCode($formation->code_formation);
      foreach($idform as $idformee){
        if( $idformee->libelle != "" )
          $this->data[$idformee->id_formation] = $idformee->libelle;
        else
          $this->data[$idformee->id_formation] = $idformee->code_formation;
      }
    }
    
  }
 
  public function getData($form){
    return ($this->data);
  }
 
  public function getLabel($key){
    if(isset($this->data[$key]))
      return $this->data[$key];
    else
      return null;
  }
 
}