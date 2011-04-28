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

        $rep->setTitle('Bienvenue sur Ogre, '.jAuth::getUserSession()->login);

    $content = '';

/*
        $content .= '
            <p>Liens temporaires : </p>
            <ul>
                    <!--<li><a href="'.jUrl::get('formations~formations:index').'">Formations CRUD</a></li>
	<li><a href="'.jUrl::get('ue~ue:index').'">Ue CRUD</a></li>
	<li><a href="'.jUrl::get('etudiants~import:index2').'">Import PV</a></li> 
                    <li><a href="'.jUrl::get('ue~saisie_epreuve:intro').'">Saisie notes/epreuve</a></li> -->
            </ul>
        ';
        */
	
        $content .='<div class="widget-container">';
        $content.= jZone::get('etudiants~recherche');
        $content.= jZone::get('etudiants~widget_etudiant');
        $content.= jZone::get('ue~widget_note');
	$content.= jZone::get('phpexcel~widget_exportformation');
	$content.= jZone::get('phpexcel~widget_importPV');
        $content.='</div>';
        /*
        $content .= '<h3 style="margin-top:50px;">A destination des testeurs</h3>
        
        <p> L\'application dans son état actuelle <strong>ne gère pas encore</strong> : </p>
       
        <ul class="liste">
            <li>Les cas particuliers au niveau des étudiants (formule spéciale pour les UEs, etc)</li>
            <li>L\'import des anciennes notes</li>
            <li>L\'historique d\'un etudiant (ne marche que partiellement)</li>
            <li>L\'affichage et l\'export des procès verbaux pour les jurys</li>
        </ul>
        
        <p>Bugs connus: </p>
         <ul class="liste">
            <li>Toutes modifications d\'un étudiant entraine la suppression de ses options</li>
        </ul>
        
        ';
        */
        
        
        
        $rep->body->assign('MAIN', $content);


        return $rep;
    }
}
