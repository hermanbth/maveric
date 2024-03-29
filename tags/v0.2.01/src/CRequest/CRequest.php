<?php
/**
* Parse the request and identify controller, method and arguments.
* Förutom att dela upp kontroller, metod och argument i förfrågan så sparas de också.
*
* @package MavericCore
*/
class CRequest {
	
	// medlemsvariabler
	public $cleanUrl = "";
	public $querystringUrl = "";
	/*
	public $baseUrl = "";
	public $currentUrl = "";
	public $requestUri = "";
	public $scriptName = "";
	public $request = "";
	public $splits = array();
	public $controller = "";
	public $method = "";
	public $arguments = array();
    */
	/**
	* Constructor
	*
	* Decide which type of url should be generated as outgoing links.
	* default      = 0      => index.php/controller/method/arg1/arg2/arg3
	* clean        = 1      => controller/method/arg1/arg2/arg3
	* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
	*
	* @param boolean $urlType integer 
	*/
	public function __Construct($urlType = 0){
		$this->cleanUrl = $urlType = 1 ? true : false;
		$this->querystringUrl = $urlType = 2 ? true : false;
	}//avslutar konstruktor
	
	/**
   * Create a url in the way it should be created.
   *
   * @param $url string the relative url or the controller
   * @param $method string the method to use, $url is then the controller or empty for current
   */
	public function CreateUrl($url=null, $method=null) {
		// If fully qualified just leave it.
		if(!empty($url) && (strpos($url, '://') || $url[0] == '/')) {
		  return $url;
		}

		// Get current controller if empty and method choosen
		if(empty($url) && !empty($method)) {
		  $url = $this->controller;
		}

		// Create url according to configured style
		$prepend = $this->baseUrl;
		if($this->cleanUrl) {
		  ;
		} elseif ($this->querystringUrl) {
		  $prepend .= 'index.php?q=';
		} else {
		  $prepend .= 'index.php/';
		}
		return $prepend . rtrim("$url/$method", '/');
	}// avslutar metod
	
	
   /**
	* Parse the current url request and divide it in controller, method and arguments.
	* Förfrågan/länken tolkas och delas upp i kontroller, metod och olika argument.
	* Calculates the base_url of the installation. Stores all useful details in $this.
	*
	* @param $baseUrl string use this as a hardcoded baseurl.
	*/
	public function Init($baseUrl = null) {

		// Take current url and divide it in controller, method and arguments
		$requestUri = $_SERVER['REQUEST_URI'];
		$scriptPart = $scriptName = $_SERVER['SCRIPT_NAME'];    

		// Check if url is in format controller/method/arg1/arg2/arg3
		if(substr_compare($requestUri, $scriptName, 0)) {
		  $scriptPart = dirname($scriptName);
		}

		// Set query to be everything after base_url, except the optional querystring
		$query = trim(substr($requestUri, strlen(rtrim($scriptPart, '/'))), '/');
		$pos = strcspn($query, '?');
		if($pos) {
		  $query = substr($query, 0, $pos);    
		}
		
		// Check if this looks like a querystring approach link
		if(substr($query, 0, 1) === '?' && isset($_GET['q'])) {
		  $query = trim($_GET['q']);
		}
		$splits = explode('/', $query);
		
		// Set controller, method and arguments
		$controller =  !empty($splits[0]) ? $splits[0] : 'index';
		$method     =  !empty($splits[1]) ? $splits[1] : 'index';
		$arguments = $splits;
		unset($arguments[0], $arguments[1]); // remove controller & method part from argument list
		
		// Prepare to create current_url and base_url
		$currentUrl = $this->FindCurrentUrl();
		$parts       = parse_url($currentUrl);
		$baseUrl     = !empty($baseUrl) ? $baseUrl : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');
		
		// Store it
		$this->baseUrl     = rtrim($baseUrl, '/') . '/';
		$this->currentUrl  = $currentUrl;
		$this->requestUri  = $requestUri;
		$this->scriptName  = $scriptName;
		$this->query        = $query;
		$this->splits        = $splits;
		$this->controller    = $controller;
		$this->method        = $method;
		$this->arguments    = $arguments;
	}// avslutar metod

	/**
	* Find the url to the current page. 
	*/
	public function FindCurrentUrl() {
		$url = "http";
		$url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
		$url .= "://";
		$serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
		(($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
		$url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
		return $url;
	}// avslutar metod

}//Avslutar klassen