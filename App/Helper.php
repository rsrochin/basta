<?php
namespace App;
use PPI\Core;
class Helper {
	protected static $_protected_symbols = array();
	static function includeTemplate($name, $pt_vars = array()) {
		$config = Core::getConfig();
		$layout = $config->get("layout");
		$file = APPFOLDER . "View/" . $layout->get("view_theme");
		$file .= "/{$name}.php";
		if(file_exists($file)) {
			if(!is_array($pt_vars)) $pt_vars = array($pt_vars);
			foreach($pt_vars as $key => $var) {
				if(!in_array($key, \App\Helper::$_protected_symbols)) $$key = $var;
			}
			require_once($file);
		}
	}
	static function requestInfo() {
		static $result;
		if(isset($result)) return $result;
		$result = array(
			"path" => "",
			"get" => array()
		);
		$uri = $_SERVER["REQUEST_URI"];
		$parts = parse_url($uri);
		$result["path"] = $parts["path"];
		if(isset($parts["query"])) {
			foreach(explode("&", $parts["query"]) as $param) {
				if(strpos($param, "=")) {
					list($name, $value) = explode("=", $param, 2);
				} else {
					$name = $param;
					$value = true;
				}
				$result["get"][$name] = $value;
			}
		}
		return $result;
	}
	static function log($text, $type = "general") {
		static $log_fh = null;
		var_dump(debug_backtrace());
		if(is_null($log_fh)) {
			$log_fh = fopen(APPFOLDER . "Logs/debug.log", "a+");
		}
	}
	static function ellipseAt($text, $position) {
		if(strlen($text) > $position) {
			$text = substr($text, 0, $position) . "&hellip;";
		}
		return $text;
	}
	static function linkFor($type, $parameters = null, $class_attrib = null) {
		static $oTopCatM, $oCatZeroM, $oPageM;
		if(is_null($oTopCatM)) {
			$oTopCatM = new \App\Model\Topcat();
			$oCatZeroM = new \App\Model\Catzero();
			$oPageM = new \App\Model\Page();
		}
		if(!is_array($parameters)) $parameters = array($parameters);
		// add lookup for APP_Interface_Routable
		$info = \App\Helper::requestInfo();
		$helper =new \App\Helper\View();
		
		switch(strtolower($type)) {
			case "pages":
				$page = $oPageM->find($parameters["id"]);
				if(empty($page)) throw new PPI_Exception("No Page Found for ID {$parameters['id']}");
				$catzero = $oCatZeroM->find($page["catzero_id"]);
				$topcat = $oTopCatM->find($page["topcat_id"]);
				$link = $helper->makePageIDLink($topcat["title"], $catzero["title"], $page["title"], $page["page_id"]);
				if(!isset($parameters["text"])) $parameters["text"] = $page["title"];
				break;
			case "topcats":
				$topcat = $oTopCatM->find($parameters["id"]);
				if(empty($topcat)) throw new PPI_Exception("No Topcat Found for ID {$parameters['id']}");
				$link = $helper->makeTopcatLink($topcat["title"], $topcat["id"]);
				if(!isset($parameters["text"])) $parameters["text"] = $topcat["title"];
				break;
			case "catzero":
				$catzero = $oCatZeroM->find($parameters["id"]);
				if(empty($catzero)) throw new PPI_Exception("No Catzero Found for ID {$parameters['id']}");
				$topcat = $oTopCatM->find($catzero["topcat_id"]);
				$link = $helper->makeCatzeroLink($topcat["title"], $catzero["title"], $catzero["id"]);
				if(!isset($parameters["text"])) $parameters["text"] = $catzero["title"];
				break;
			case "search_terms":
				$link = "/search/index/{$parameters['title']}";
				if(!isset($parameters["text"])) $parameters["text"] = $parameters["title"];
				break;
			default:
				$link = "#err-no-handler:{$type}";
		}
		return "<a href=\"{$link}\">{$parameters['text']}</a>";
	}
}
?>