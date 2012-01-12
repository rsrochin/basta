<?php
namespace App;
class SiteManager {

	/**
	 * The environment name
	 *
	 * @var null|string
	 */
	protected $_envName = null;

	/**
	 * The config block
	 *
	 * @var null|string
	 */
	protected $_configBlock = null;

	/**
	 * The site mode
	 *
	 * @var null|string
	 */
	protected $_siteMode = null;

	function __construct(array $options = array()) {
		
		$this->_configBlock = 'main';
		$request = new \PPI\Request();
		switch($request->server('SERVER_NAME')) {

			case 'localhost':
			default:
				$this->_envName = 'local';
				$this->_connectionsPath = APPFOLDER . 'Config/Connections/local.php';
				$this->_configFile = 'General/local.ini';
				break;
		}
	}

	/**
	 * Get the site mode
	 *
	 * @return null|string
	 */
	function getSiteMode() {
		return $this->_siteMode;
	}

	/**
	 * Get the config block
	 *
	 * @return null|string
	 */
	function getConfigBlock() {
		return $this->_configBlock;
	}

	/**
	 * Get the environment name
	 *
	 * @return string
	 */
	function getEnvName() {
		return $this->_envName;
	}
	
	/**
	 * The path to the datasource connections file
	 * 
	 * @return string
	 */
	function getConnectionsPath() {
		return $this->_connectionsPath;
	}
	
	/**
	 * Get the config file path
	 * 
	 * @return string
	 */
	function getConfigFile() {
		return $this->_configFile;
	}

}
