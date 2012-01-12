<?php
/**
 * Shared Application Controller Class
 * this file is used to create generic controller functions
 * to share accross all of your application Controllers
 */
namespace App\Controller;
use PPI\Core, PPI\Core\CoreException;

abstract class Application extends \PPI\Controller {

	/**
	 * The site options
	 *
	 * @var array
	 */
	protected $_siteOptions = array();

	/**
	 * The View Helper
	 *
	 * @var object|null
	 */
	protected $_viewHelper = null;

	/**
	 * The page model
	 *
	 * @var null|object
	 */
	protected $_page = null;

	/**
	* The keyword if the referrer was from a search engine results page.
	*
	* @var string
	*/
	protected $_searchEngineQuery = '';

	/**
	* The bool to determine if the page view came from a search engine results page
	*
	* @var bool
	*/
	protected $_detectedSearchEngineQuery = false;

	/**
	 * The Page name
	 */
	protected $pageName = '';
	protected $_inner   = false;

	/**
	 * The actions preDispatch method
	 * 
	 * @return void
	 */
	function preDispatch() {
		$this->_viewHelper = new \App\Helper\View();
	}

	function render($template, $params = array(), $options = array()) {

		$params['pageName']     = $this->getPageName();
		$params['helper']       = $this->_viewHelper;
		$params['siteOptions']  = $this->_siteOptions;

		if ($this->checkAdmin()) {
			$this->loadAdmin();
		} else {
			$inner = $this->isInner() ? 'inner' : '';
			$this->addcss('grid','website',$inner);
		}

		parent::render($template, $params);
	}

	/**
	 * Render a cached view
	 *
	 * @param string $template
	 * @param array $params
	 * @param array $options
	 * @return mixed
	 */
	function cachedRender($template, array $params = array(), array $options = array()) {

		$params['helper'] = $this->_viewHelper;
		$params['siteOptions'] = $this->_siteOptions;
		$options = array(
			'partial' => true,
			'cache' => true,
			'cacheHandler' => $this->getCache()->getHandler(),
			'cachePrefix' => 'JuvaSoft'
		) + $options;
		return parent::render($template, $params, $options);
	}

	function loadAdmin() {
		$this->addcss('bootstrap.min','layout','admin');
		$this->setTemplateFile('TemplateAdmin');
	}

	function amILoggedIn() {
		return $this->getSession()->get('usuario_id') ? true : false;
	}

	function checkAdmin() {
		return $this->getSession()->get('nivel') == 1 ? true : false;
	}

	function setPageName($name = 'Dashboard') {
		$this->pageName = $name;
	}

	function getPageName() {
		return $this->pageName;
	}

	function setInner() {
		$this->_inner = true;
	}

	function isInner() {
		return $this->_inner;
	}

	function unsetInner() {
		$this->_inner = false;
	}

	/**
	 * Check if a cachedRender item exists in the cache
	 *
	 * @param string $template
	 * @param array $options
	 * @return boolean
	 */
	public function cachedRenderExists($template, array $options = array()) {

		$options = array(
			'partial' => true,
			'cache' => true,
			'cacheHandler' => $this->getCache()->getHandler(),
			'cachePrefix' => 'Juvasoft'
		) + $options;
		return parent::cachedRenderExists($template, $options);
	}

	public function getCachedRender($template, array $options = array()) {
		$options = array(
			'partial' => true,
			'cache' => true,
			'cacheHandler' => $this->getCache()->getHandler(),
			'cachePrefix' => 'Juvasoft'
		) + $options;
		return parent::getCachedRender($template, $options);
	}

	/**
	 * Get the translations for your current country
	 *
	 * @return array
	 */
	protected function getTranslations() {
		return $this->_viewHelper->getTranslations();
	}

	/**
	 * Translate the key specified
	 *
	 * @param string $key
	 * @return string
	 */
	protected function translate($key) {
		return $this->_viewHelper->translate($key);
	}

	/**
	 * Get the view helper
	 *
	 * @return \App\Helper\View
	 */
	protected function getViewHelper() {
		if($this->_viewHelper === null) {
			$this->_viewHelper = new \App\Helper\View();
		}
		return $this->_viewHelper;
	}

	/**
	 * Is the referrer url coming from a search engine?
	 *
	 * @return bool
	 */
	function isQueryFromSearchEngine() {
		$bFromSearchEngine = false;
		if(!isset($_SERVER['HTTP_REFERER'])) {
			return false;
		}
		$referer = $_SERVER['HTTP_REFERER'];
		$parsed = parse_url($referer, PHP_URL_QUERY);
		parse_str($parsed, $query);
		switch(true) {
			case strpos($referer, 'yahoo') !== false && isset($query['p']):
				$keyword = $query['p'];
				break;

			case strpos($referer, 'bing') !== false && isset($query['q']):
				$keyword = $query['q'];
				break;

			case strpos($referer, 'google') !== false && isset($query['q']):
				$keyword = $query['q'];
				break;

			case strpos($referer, 'aol') !== false && isset($query['query']):
				$keyword = $query['query'];
				break;

			default:
				$keyword = '';
				break;
		}
		$this->_searchEngineQuery = $keyword;
		return $keyword !== '';
	}

	/**
	 * Get the keyword that the search engine used on our site
	 *
	 * @return string
	 */
	function getSearchEngineQuery() {
		return $this->_searchEngineQuery;
	}

	/**
	 * Set a site option
	 *
	 * @param string $key
	 * @param mixed $val
	 * @return void
	 */
	function setSiteOption($key, $val) {
		$this->_siteOptions[$key] = $val;
	}

}