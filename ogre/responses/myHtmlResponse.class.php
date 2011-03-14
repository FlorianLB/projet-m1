<?php
/**
* @package   ogre
* @subpackage 
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/


require_once ('jResponseHtmlLess.class.php');

class myHtmlResponse extends jResponseHtmlLess {

    public $bodyTpl = 'ogre~main';

    function __construct() {
        parent::__construct();

        global $gJConfig;
 
        $themePath = $gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/';
    
        $this->addJsLink($gJConfig->urlengine['basePath'].'jelix/jquery/jquery.js');
    
       $this->addCSSLink($themePath.'css/base.css');
       $this->addCSSLink($themePath.'css/form.css');
       $this->addCssLinkLess($themePath.'css/style.less');
    }

    protected function doAfterActions() {
        // Include all process in common for all actions, like the settings of the
        // main template, the settings of the response etc..

        $this->body->assignIfNone('MAIN','<p>no content</p>');
        
        if($this->title == '')
            $this->setTitle();
    }
    
    
    public function setTitle($title = null){
        if($title) {
            $this->body->assign('title', $title);
            $this->title = $title . ' | Ogre';
        }
        else {
            $this->body->assign('title', '');
            $this->title = 'Ogre';
        }
    }
}
