<?php
namespace App\Helper;
use PPI\Core;
class View {

	/**
	 * The base url
	 *
	 * @var null
	 */
	protected $_baseUrl = null;

	protected $_loadedAds = null;

	protected $_adsHelper = null;
	
	protected $_config = null;
	

	/**
	 * The loaded translations for the current app
	 *
	 * @var null
	 */
	protected $_loadedTranslations = null;

	function __construct() {
		
		$this->_config = Core::getConfig();
		
	}

	/**
	 * Convert an entitie's title to url safe characters
	 *
	 * @param  $title
	 * @return mixed
	 */
	function urlSafeTitle($title) {
		return str_replace(array('"', ' ', '/'), array('', '-', '~'), $title);
	}

	function getBaseUrl() {
		if($this->_baseUrl === null) {
			$this->_baseUrl = $this->_config->system->base_url;
		}
		return $this->_baseUrl;
	}

	/**
	 * escape the output
	 *
	 * @param string $var
	 * @return string
	 */
	function escape($var) {
		return htmlentities($var, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Get rid of all HTML elements for XSS site injection and escape the output
	 *
	 * @param string $var
	 * @return string
	 */
	function htmlEscape($var) {
		return htmlentities(strip_tags($var), ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Get the translations for your current country
	 *
	 * @return array
	 */
	function getTranslations() {
		$country = $this->_config->country;
		include_once(CONFIGPATH . 'translations.php');
		return isset($translations[$country]) ? $translations[$country] : array();
	}

	/**
	 * Translate the key specified
	 *
	 * @param string $key
	 * @return string
	 */
	function translate($key) {
		if($this->_loadedTranslations === null) {
			$this->_loadedTranslations = $this->getTranslations();
		}
		return isset($this->_loadedTranslations[$key]) ? $this->_loadedTranslations[$key] : $key;
	}

}