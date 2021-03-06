<?php
/**
* @package     jelix
* @subpackage  core_response
* @author      Baptiste Toinot
* @copyright   2008 Baptiste Toinot
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
*
*/
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');

/**
* Sitemap 0.9 response
*
* @package jelix
* @subpackage core_response
* @link http://www.sitemaps.org/
* @since 1.2
*/
class jResponseSitemap extends jResponse {

    /**
    * Ident of the response type
    * @var string
    */
    protected $_type = 'sitemap';

    /**
    * Frequency change url
    * @var array
    */
    protected $allowedChangefreq = array('always', 'hourly', 'daily', 'weekly',
                                         'monthly', 'yearly', 'never');
    /**
    * Maximum number of URLs for a sitemap index file
    * @var int
    */
    protected $maxSitemap = 1000;

    /**
    * Maximum number of URLs for a sitemap file
    * @var int
    */
    protected $maxUrl = 50000;

    /**
    * List of URLs for a sitemap index file
    * @var array()
    */
    protected $urlSitemap;

    /**
    * List of URLs for a sitemap file
    * @var array()
    */
    protected $urlList;

    /**
     * The template container
     * @var jTpl
     */
    public $content;

    /**
     * Selector of the template file
     * @var string
     */
    public $contentTpl;

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct() {
        $this->content  = new jTpl();
        $this->contentTpl = 'jelix~sitemap';
        parent::__construct();
    }

    /**
     * Generate the content and send it
     * Errors are managed
     * @return boolean true if generation is ok, else false
     */
    final public function output() {
        $this->_headSent = false;
        $this->_httpHeaders['Content-Type'] = 'application/xml;charset=UTF-8';
        $this->sendHttpHeaders();
        echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";

        if (!is_null($this->urlSitemap)) {
            echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            $this->_headSent = true;
            $this->contentTpl = 'jelix~sitemapindex';
            $this->content->assign('sitemaps', $this->urlSitemap);
        } else {
            echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            $this->_headSent = true;
            $this->content->assign('urls', $this->urlList);
        }

        $this->content->display($this->contentTpl);

        if ($this->hasErrors()) {
            echo $this->getFormatedErrorMsg();
        }

        if (!is_null($this->urlSitemap)) {
            echo '</sitemapindex>';
        } else {
            echo '</urlset>';
        }
        return true;
    }

    /**
     * output errors
     */
    final public function outputErrors() {
        if (!$this->_headSent) {
            if (!$this->_httpHeadersSent) {
                header("HTTP/1.0 500 Internal Server Error");
                header('Content-Type: text/xml;charset=UTF-8');
            }
            echo '<?xml version="1.0" encoding="UTF-8"?>';
        }

        echo '<errors xmlns="http://jelix.org/ns/xmlerror/1.0">';
        if ($this->hasErrors()) {
            echo $this->getFormatedErrorMsg();
        } else {
            echo '<error>Unknow Error</error>';
        }
        echo '</errors>';
    }

    /**
     * Format error messages
     * @return string formated errors
     */
    protected function getFormatedErrorMsg() {
        $errors = '';
        foreach ($GLOBALS['gJCoord']->errorMessages as $e) {
           $errors .=  '<error xmlns="http://jelix.org/ns/xmlerror/1.0" type="'. $e[0] .'" code="'. $e[1] .'" file="'. $e[3] .'" line="'. $e[4] .'">'. $e[2] .'</error>'. "\n";
        }
        return $errors;
    }

    /**
     * add a URL in a sitemap file
     * @param string $loc URL of the page
     * @param string $lastmod The date of last modification of the file
     * @param string $changefreq How frequently the page is likely to change
     * @param string $priority The priority of this URL relative to other URLs
     * @return void
     */
    public function addUrl($loc, $lastmod = null, $changefreq = null, $priority = null) {

        if (isset($loc[2048]) || count($this->urlList) >= $this->maxUrl) {
            return false;
        }

        $url = new jSitemapUrl();
        $url->loc = 'http'. (empty($_SERVER['HTTPS']) ? '' : 's') .'://'. $_SERVER['HTTP_HOST'] . $loc;

        if (($timestamp = strtotime($lastmod))) {
            $url->lastmod = date('c', $timestamp);
        }

        if ($changefreq && in_array($changefreq, $this->allowedChangefreq)) {
            $url->changefreq = $changefreq;
        }

        if ($priority && is_numeric($priority) && $priority >= 0 && $priority <= 1) {
            $url->priority = sprintf('%0.1f', $priority);
        }

        $this->urlList[] = $url;
    }

    /**
     * add a URL in a sitemap file
     * @param string $loc URL of sitemap file
     * @param string $lastmod The date of last modification of the sitemap file
     * @return void
     */
    public function addSitemap($loc, $lastmod = null) {

        if (isset($loc[2048]) || count($this->urlSitemap) >= $this->maxSitemap) {
            return false;
        }

        $sitemap = new jSitemapIndex();
        $sitemap->loc = 'http'. (empty($_SERVER['HTTPS']) ? '' : 's') .'://'. $_SERVER['HTTP_HOST'] . $loc;

        if (($timestamp = strtotime($lastmod))) {
            $sitemap->lastmod = date('c', $timestamp);
        }

        $this->urlSitemap[] = $sitemap;
    }

    /**
     * Add URLs automatically from urls.xml
     * @return void
     */
    public function importFromUrlsXml() {
        $urls = $this->_parseUrlsXml();
        foreach ($urls as $url) {
            $this->addUrl($url);
        }
    }

    /**
     * Return pathinfo URLs automatically from urls.xml
     * @return array
     */
    public function getUrlsFromUrlsXml() {
        return $this->_parseUrlsXml();
    }

    /**
     * Submitting a sitemap by sending an HTTP request
     * @return boolean
     */
    public function ping($uri) {
        $parsed_url = parse_url($uri);
        if (!$parsed_url || !is_array($parsed_url)) {
            return false;
        }
        $http = new jHttp($parsed_url['host']);
        $http->get($parsed_url['path'] . '?' . $parsed_url['query']);
        if ($http->getStatus() != 200) {
            return false;
        }
        return true;
    }

    /**
     * Parse urls.xml and return pathinfo URLs
     * @return array
     */
    protected function _parseUrlsXml() {
        global $gJConfig;

        $urls = array();
        $significantFile = $gJConfig->urlengine['significantFile'];
        $entryPoint = $gJConfig->urlengine['defaultEntrypoint'];
        $snp = $gJConfig->urlengine['urlScriptIdenc'];

        $file = JELIX_APP_TEMP_PATH.'compiled/urlsig/' . $significantFile .
                '.' . rawurlencode($entryPoint) . '.entrypoint.php';

        if (file_exists($file)) {
            require $file;
            $dataParseUrl = $GLOBALS['SIGNIFICANT_PARSEURL'][$snp];
            foreach ($dataParseUrl as $k => $infoparsing) {
                if ($k == 0) {
                    continue;
                }
                // it is not really relevant to get URL that are not complete
                // but it is difficult to know automatically what are real URLs
                if (preg_match('/^([^\(]*)/', substr($infoparsing[2], 2, -2), $matches)) {
                    $urls[] = $matches[1];
                }
            }
        }
        return $urls;
    }
}

/**
 * Content of a URL
 * @package jelix
 * @subpackage core_response
 * @since 1.2
 */
class jSitemapUrl {

    /**
     * URL of the page
     * @var string
     */
    public $loc;

    /**
     * The date of last modification of the page
     * @var string
     */
    public $lastmod;

    /**
     * How frequently the page is likely to change
     * @var string
     */
    public $changefreq;

    /**
     * The priority of this URL relative to other URLs
     * @var string
     */
    public $priority;
}

/**
 * Content of a sitemap file
 * @package    jelix
 * @subpackage core_response
 * @since 1.2
 */
class jSitemapIndex {

    /**
     * URL of the sitemap file
     * @var string
     */
    public $loc;

    /**
     * The date of last modification of the sitemap file
     * @var string
     */
    public $lastmod;
}
