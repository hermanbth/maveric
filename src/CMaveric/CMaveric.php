<?php
/**
* Main class for Maveric, holds everything.
* Skapad som ett "singleton pattern", vilket innebär att det endast går att skapa ett objekt av klassen.
*
* @package MavericCore
*/

// implements ISingleton anger att klassen måste använda vissa metoder. I står för interface.
// alla klasser i ramverket som har angivet yckelordet implements ISingleton kommer att 
// använda interfacet ISingelton som finns i filen src/ISingleton/ISingleton.php.
class CMaveric implements ISingleton {
	// medlemsvariabler
	private static $instance = null;
	private $config = array();
	private $data = array();
	private $request = array();  

	/**
	* Constructor
	*/
	protected function __Construct() {
		// include the site specific config.php and create a ref to $maveric to be used by config.php, $maveric går då att använda direkt i config.php. 
		$maveric = &$this;
		require(MAVERIC_SITE_PATH.'/config.php');
	}// avslutar konstruktor

	/**
	* Singleton pattern. Get the instance of the latest created object or create a new one.
	* @return CMaveric The instance of this class.
	*/
	public static function Instance() {
		if(self::$instance == null) {
		 self::$instance = new CMaveric();
		}
		return self::$instance;
	}// avslutar metod

	/**
	* Frontcontroller, check url and route to controllers.
	* Förfrågan pekar på en metod i en kontrollerklass så FrontController-metoden ska skicka förfrågan dit. Första steget är att förfrågan tolkas och
	* plockas isär. Sedan görs en koll om klassen och metoden som ska plockas fram finns. Därefter sker anrop till metoden i den klassen och den
	* metoden/klassen sköter hanteringen av förfrågan. Koppling finns nu mellan förfrågan och metode i kontroller-klassen.
	*/
	public function FrontControllerRoute() {

		// Step 1
		// Take current url and divide it in controller, method and parameters
		$config = $this->GetConfig();
		$this->request = new CRequest($config['url_type']);
		$this->request->Init($config['base_url']);
		// hämtar ut kontroller, metod och paramterar från klassens medlemmar.
		$controller = $this->request->GetController();
		$method     = $this->request->GetMethod();
		$arguments  = $this->request->GetArguments();




		// Step 2
		// Check if there is a callable method in the controller class, if then call it

		// Is the controller enabled in config.php?
		// listan med kontrollers gås igenom
		$config = $this->GetConfig();
		$controllerExists    = isset($config['controllers'][$controller]);
		$controllerEnabled    = false;
		$className             = false;
		$classExists           = false;

		if($controllerExists) {
			// print("inne i controllerExists-if");
			
			$config = $this->GetConfig();
			$controllerEnabled    = ($config['controllers'][$controller]['enabled'] == true);
			$className               = $config['controllers'][$controller]['class'];
			$classExists           = class_exists($className);
		}//avslutar if

		/*
		print("controllerExists: " . $controllerExists . "<br />");
		print("controllerEnabled: " . $controllerEnabled . "<br />");
		print("classExists: " . $classExists . "<br /><br />");
		*/

		// Check if controller has a callable method in the controller class, if then call it
		// använder Reflection som är bra att använda när vi på dynamsikt vis vill anropa en metod i valfri klass.
		// Reflektion innebär att man kan få en lista med metoder och metodparametrar från en klass
		if($controllerExists && $controllerEnabled && $classExists) {
			$rc = new ReflectionClass($className);
			// kontrollern får ett interface som ordnar så att kontrollern får tillgång till metoden Index som 
			// anropas om kontrollern anges utan en metod. T ex blir länken controller/ då controller/index. 
			if($rc->implementsInterface('IController')) {
				if($rc->hasMethod($method)) {
					$controllerObj = $rc->newInstance();
					$methodObj = $rc->getMethod($method);
					if($methodObj->isPublic()) {
						$methodObj->invokeArgs($controllerObj, $arguments);
					}else {
						die("404. " . get_class() . ' error: Controller method not public.');          
					}
				} else {
					die("404. " . get_class() . ' error: Controller does not contain method.');
				}
			} else {
				die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
			}//avslutar else
		}else {
			die('404. Page is not found.');
		}// avslutar else
	}// avslutar metod

	/**
	* Theme Engine Render, renders the views using the selected theme (alltså svaret på förfrågan).
	* Allt i $data-medlemen ska skrivas ut.
	* Delar från $data-arrayen läggs i olika vyer och vyerna kommer alltså innehålla olika delar av den färdiga webbsidan.
	* Tema motor sätter ihop vyerna och skriver ut dem så att det blir en webbsida.
	* Tema? Ett koncept för stylesheets, bilder, funktioner och template-filer. Meningen med tema är att det ska gå att styla webbplatsen 
	* på flexibelt vis, t ex ändra färger i stylesheeten (tänk Wordpress). Men om vill ändra en hårdkodad del av webbplatsen, t ex footer, så krävs ändring
	* i $data-arrayen. Sådan ändring kan ske via databas men ibland krävs ändring i template-filer.
	*/
	public function ThemeEngineRender() {
		
		// Get the paths and settings for the theme
		$config = $this->GetConfig();
		$themeName    = $config['theme']['name'];
		$themePath    = MAVERIC_INSTALL_PATH . "/theme/{$themeName}";
		$baseUrl = $this->request->GetBaseUrl();
		$themeUrl      = $baseUrl . "theme/{$themeName}";
	   
		// Add stylesheet path to the $maveric->GetData() array
	    $this->SetData('stylesheet', "{$themeUrl}/style.css");
		
		// Include the global functions.php and the functions.php that are part of the theme
		$maveric = &$this;
		$globalFunctionsPath = $baseUrl . "theme/functions.php";
		if(is_file($globalFunctionsPath)){
			include $globalFunctionsPath;
		}//avslutar if
		$functionsPath = "{$themePath}/functions.php";
		if(is_file($functionsPath)) {
			include $functionsPath;
		}// avslutar if

		// Extract $maveric->data to own variables and handover to the template file
		// alltså: nycklarna i $data-arrayen blir variabler i template-filen
		extract($this->GetData());     
		include("{$themePath}/default.tpl.php");
		
	}// avslutar metod

	// sätter innehållet i medlemsvariabler
	public function SetConfig($key, $value){
		$this->config[$key] = $value;
	}

	public function SetData($key, $value){
		$this->data[$key] = $value;
	}

	public function SetRequest($key, $value){
		$this->request[$key] = $value;
	}

	// hämtar innehållet i medlemsvariabler   
	public function GetConfig(){
		return $this->config;
	}

	public function GetData(){
		return $this->data;
	}

	public function GetRequest(){
		return $this->request;
	}
  
}//klassen avslutas   