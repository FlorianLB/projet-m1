<?php
/**
* @package   ogre
* @subpackage ue
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class anneeCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('redirect');

        jClasses::inc('utils~AnneeSuivante');
        
        jMessage::add("Nouvelle année créée");
        AnneeSuivante::creationAnnee();
        $rep->action = 'ogre~default:index';
                return $rep;
    }
}

