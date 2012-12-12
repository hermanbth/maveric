<?php
/**
* Parse the request and identify controller, method and arguments.
* Förutom att dela upp kontroller, metod och argument i förfrågan så sparas de också.
*
* @package MavericCore
*/
class CRequest {
	
	// medlemsvariabler
	private $cleanUrl = "";
	private $querystringUrl = "";
	private $baseUrl = "";
	private $currentUrl = "";
	private $requestUri = "";
	private $scriptName = "";
	private $request = "";
	private $splits = array();
	private $controller = "";
	private $method = "";
	private $arguments = array();

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
		$this->SetCleanUrl($urlType= 1 ? true : false);
		$this->SetQuerystringUrl($urlType= 2 ? true : false);
	}//avslutar konstruktor
	
   /**
	* Parse the current url request and divide it in controller, method and arguments.
	* Förfrågan/länken tolkas och delas upp i kontroller, metod och olika argument.
	* Calculates the base_url of the installation. Stores all useful details in $this.
	*
	* @param $baseUrl string use this as a hardcoded baseurl.
	*/
	public function Init($baseUrl = null) {

		$requestUri = $_SERVER['REQUEST_URI'];
		$scriptName = $_SERVER['SCRIPT_NAME'];    
		
		// Compare REQUEST_URI and SCRIPT_NAME as long they match, leave the rest as current request.
		$i=0;
		$len = min(strlen($requestUri), strlen($scriptName));
		while($i<$len && $requestUri[$i] == $scriptName[$i]) {
		  $i++;
		}
		$request = trim(substr($requestUri, $i), '/');
	  
		// Remove the ?-part from the query when analysing controller/metod/arg1/arg2
		$queryPos = strpos($request, '?');
		if($queryPos !== false) {
		  $request = substr($request, 0, $queryPos);
		}
		
		// Check if request is empty and querystring link is set
		if(empty($request) && isset($_GET['q'])) {
		  $request = trim($_GET['q']);
		}
		$splits = explode('/', $request);
		
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
		$this->SetBaseUrl(rtrim($baseUrl, '/') . '/');
		$this->SetCurrentUrl($currentUrl);
		$this->SetRequestUri($requestUri);
		$this->SetScriptName($scriptName);
		$this->SetRequest($request);
		$this->SetSplits($splits);
		$this->SetController($controller);
		$this->SetMethod($method);
		$this->SetArguments($arguments);
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

	/**
	* Create a url in the way it should be created.
	* H'nsyn tas alltså till vilken typ av länk som ska skapas.
	*/
	public function CreateUrl($url=null) {
		$prepend = $this->GetBaseUrl();
		if($this->GetCleanUrl()) {
		;
	} elseif ($this->GetQuerystringUrl()) {
		$prepend .= 'index.php?q=';
	} else {
		$prepend .= 'index.php/';
	}
		return $prepend . rtrim($url, '/');
	}// avslutar metod
	
	// Set-metoder sätter värden på medlemsvariabler
	public function SetCleanUrl($value){
		$this->cleanUrl = $value;
	}
	public function SetQuerystringUrl($value){
		$this->querystringUrl = $value;
	}
	public function SetBaseUrl($value){
		$this->baseUrl = $value;
	}
	public function SetCurrentUrl($value){
		$this->currentUrl = $value;
	}
	public function SetRequestUri($value){
		$this->requestUri = $value;
	}
	public function SetScriptName($value){
		$this->scriptName = $value;
	}
	public function SetRequest($value){
		$this->request = $value;
	}
	public function SetSplits($value){
		$this->splits = $value;
	}
	public function SetController($value){
		$this->controller = $value;
	}
	public function SetMethod($value){
		$this->method = $value;
	}
	public function SetArguments($value){
		$this->arguments = $value;
	}
	
	// Get-metoder hämtar värden hos medlemsvariabler
	public function GetCleanUrl(){
		return $this->cleanUrl;
	}
	public function GetQuerystringUrl(){
		return $this->querystringUrl;
	}
	public function GetBaseUrl(){
		return $this->baseUrl;
	}
	public function GetCurrentUrl(){
		return $this->currentUrl;
	}
	public function GetController(){
		return $this->controller;
	}
	public function GetMethod(){
		return $this->method;
	}
	public function GetArguments(){
		return $this->arguments;
	}
	
	

}//Avslutar klassen