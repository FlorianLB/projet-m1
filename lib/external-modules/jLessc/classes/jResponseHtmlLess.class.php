<?php

require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

require_once(LIB_PATH.'lessphp/lessc.inc.php');

class jResponseHtmlLess extends jResponseHtml {
     
     private $less_path = '';
     
     private $www_path = '';
     
     private static $instance;

     
     function __construct(){
          
         $this->less_path = JELIX_APP_WWW_PATH.'less';
         //$this->less_path = '/less';
         
         $this->www_path = $GLOBALS['gJConfig']->urlengine['basePath'].'less/';
         
          parent::__construct();
     }
     
     
     /**
      * @see jResponseHtml::addCSSLink
      * @param String $name Optional name of your css stylesheet 
      */
     final public function addCSSLinkLess ($src, $params=array (), $forIE=false, $name = false){
     
          if( substr($src, -5) == '.less' ){
               
               if($name)
                    $srcName = $name.'.css';
               else{
                    $srcSegment = explode('/', $src);
                    $srcName = array_pop($srcSegment);
                    $srcName = substr($srcName, 0, -5).'.css';
               }
               $src = JELIX_APP_PATH.'www./'.$src;
               jFile::createDir( $this->less_path );
               
               
               $fileName = $this->less_path.'/'.$srcName;
               $fileNameFinal =  $this->www_path.$srcName;
               
               
               $mTimeOriginal = is_file($src) ? filemtime($src) : -1;
               $mTimeLess =  is_file($fileName) ? filemtime($fileName) : -1;

 
               if( ($mTimeOriginal > $mTimeLess)){
                    $content = jFile::read( $src );
                    $lessc = self::getLessc();
                    jFile::write( $fileName, $lessc->parse($content) );
               }
               parent::addCSSLink($fileNameFinal, $params, $forIE);
          }
          else
               parent::addCSSLink($src , $params, $forIE);
         
     }
     
     private static function getLessc($fname = null){
          if (!isset(self::$instance)) {
               self::$instance = new lessc($fname);
          }
          return self::$instance;
     }
     
}