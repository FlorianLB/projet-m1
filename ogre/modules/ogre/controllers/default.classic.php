<?php
/**
* @package   ogre
* @subpackage ogre
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






        $content = '<p>Ok nikel, tout marche</p>
            <p>Liens temporaires : </p>
            <ul>
                    <li><a href="'.jUrl::get('formations~formations:index').'">Formations CRUD</a></li>
                    <li><a href="'.jUrl::get('etudiants~etudiants:index').'">Etudiants CRUD</a></li>
	<li><a href="'.jUrl::get('ue~ue:index').'">Ue CRUD</a></li>
                    <li><a href="'.jUrl::get('etudiants~import:index').'">Import APOGEE</a></li>
            </ul>
        ';
        
        $rep->body->assign('MAIN', $content);


        return $rep;
    }
}
