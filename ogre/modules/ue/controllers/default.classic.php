<?php
/**
* @package   ogre
* @subpackage ue
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

       // jClasses::inc('utils~customSQL');
        //jClasses::inc('utils~Formule');

        jClasses::inc('utils~Moyenne');

        
       //$var = Formule::parseFormuleUe('SUP ( 2PA1 + 2 PE2 + 3 * evc)');
        //$vars = jDao::get('ue~epreuve')->getByUeAndType(9,"CTP1");
        //jLog::dump($vars);
        //foreach($vars as $var){
        //    //var_dump($var->id_epreuve);
        //    jLog::dump($var);
        //}
        
        jLog::dump(Moyenne::calcMoyenne(1,10905684,1));
        jLog::dump(Moyenne::calcAllMoyenne(1,10905684));
        
        
       // $var = Formule::sup('Sup ((EvC + P2 ) /2 ; P2)');
       
       //$toto = jDao::get('formations~formation')->getLastFormation();
        //foreach($toto as $ligne){
        //$annee = substr("2000-2001",5,4);
        //$annee .= "-".($annee + 1);
        //    var_dump($annee);
        //}

        //var_dump(customSQL::noteExisteDeja(33,1,10800755));

       // $rep->body->assign('MAIN',$content);
        return $rep;
    }
}

