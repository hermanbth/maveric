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
	public $config = array();
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;
	public $timer = array();
  
	/**
	* Constructor
	*/
	protected function __Construct() {
		// time page generation
		$this->timer['first'] = microtime(true); 
		
		// include the site specific config.php and create a ref to $maveric to be used by config.php, $maveric går då att använda direkt i config.php. 
		$maveric = &$this;
		require(MAVERIC_SITE_PATH.'/config.php');
		
		// Start a named session
		session_name($this->config['session_name']);
		session_start();
		$this->session = new CSession($this->config['session_key']);
		$this->session->PopulateFromSession();
		
		// Set default date/time-zone
		date_default_timezone_set($this->config['timezone']);
		
		// Create a database object.
		if(isset($this->config['database'][0]['dsn'])) {
			$this->db = new CDatabase($this->config['database'][0]['dsn']);
		}//avslutar if
		
		// Create a container for all views and theme data
        $this->views = new CViewContainer();
		
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
    // Take current url and divide it in controller, method and parameters
    $this->request = new CRequest($this->config['url_type']);
    $this->request->Init($this->config['base_url']);
    $controller = $this->request->controller;
    $method     = $this->request->method;
    $arguments  = $this->request->arguments;
    
    // Is the controller enabled in config.php?
    $controllerExists   = isset($this->config['controllers'][$controller]);
    $controllerEnabled   = false;
    $className          = false;
    $classExists         = false;

    if($controllerExists) {
      $controllerEnabled   = ($this->config['controllers'][$controller]['enabled'] == true);
      $className          = $this->config['controllers'][$controller]['class'];
      $classExists         = class_exists($className);
    }
    
    // Check if controller has a callable method in the controller class, if then call it
    if($controllerExists && $controllerEnabled && $classExists) {
      $rc = new ReflectionClass($className);
      if($rc->implementsInterface('IController')) {
         $formattedMethod = str_replace(array('_', '-'), '', $method);
        if($rc->hasMethod($formattedMethod)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($formattedMethod);
          if($methodObj->isPublic()) {
            $methodObj->invokeArgs($controllerObj, $arguments);
          } else {
            die("404. " . get_class() . ' error: Controller method not public.');          
          }
        } else {
          die("404. " . get_class() . ' error: Controller does not contain method.');
        }
      } else {
        die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
      }
    } 
    else { 
      die('404. Page is not found.');
    }
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
		// Save to session before output anything
		$this->session->StoreInSession();
	
		// Is theme enabled?
		if(!isset($this->config['theme'])) {
			return;
		}
	
		// Get the paths and settings for the theme
		$themeName    = $this->config['theme']['name'];
		$themePath    = MAVERIC_INSTALL_PATH . "/theme/{$themeName}";
		$themeUrl    = $this->request->baseUrl . "theme/{$themeName}";
	   
		// Add stylesheet path to the $maveric->GetData() array
	    $this->data['stylesheet'] = "{$themeUrl}/style.css";
		
		// Include the global functions.php and the functions.php that are part of the theme
		$maveric = &$this;
		include(MAVERIC_INSTALL_PATH . '/theme/functions.php');
		$functionsPath = "{$themePath}/functions.php";
		if(is_file($functionsPath)) {
			include $functionsPath;
		}// avslutar if

		// Extract $maveric->data and $maveric->view->data to own variables and handover to the template file
		// alltså: nycklarna i $data-arrayen blir variabler i template-filen
		extract($this->data);     
		extract($this->views->GetData());     
		include("{$themePath}/default.tpl.php");
		
	}// avslutar metod
  
}//klassen avslutas   