<?php

class ueCtrl extends jControllerDaoCrud {
 
    protected $dao = 'ue~ue';
 
    protected $form = 'ue~ueform';
	
	protected $propertiesForList = array('code_ue', 'coeff', 'credits', 'libelle', 'last_version');
 
}