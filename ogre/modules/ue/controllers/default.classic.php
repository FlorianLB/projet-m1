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
        jClasses::inc('utils~Formule');
        
        $var = Formule::parseFormuleUe('PA1 +            2 PE2 + 3 * evc');
        
        var_dump($var);
        
        //$rep->body->assign('MAIN',$content);
        return $rep;
    }
}

