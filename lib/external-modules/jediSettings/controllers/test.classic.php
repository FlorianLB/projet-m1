<?php
/**
* @package   test
* @subpackage jediSettings
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class testCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->title = "jediSettings tests";
        
        if( jAcl2::check('lol') )
        
        
        
        
        jClasses::inc('jediSettings');
        
        $content ='<h2>jediSettings module</h2>';
        
        $settings = array(
            'display.news',
            'clients.number',
            'lol'
        );
        
        $content .= '<ul>';
        foreach($settings as $key) {
            $content .= '<li><strong>'.$key.'</strong> : '.jediSettings::get($key).'</li>';
        }
        $content .= '</ul>';
        
        
        jediSettings::set('lol', 'bite');
        
        $rep->body->assign('MAIN', $content);
        return $rep;
    }
}

