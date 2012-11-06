<?php
/**
* Holding a instance of CMaveric to enable use of $this in subclasses.
* Basklass. I de klasserna som använder basklassen skriver man $this->medlemSomDeklareratsForKlassenIBasklassensConstructor.
* I klassen CMaveric blir det t ex $this->config för att komma åt config-medlemmen istället för $maveric->config som hade använts
* Om Maveric-klassen inte använt CObject-klassen.
*
* @package MavericCore
*/
class CObject {

	public $config;
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;
	
	/**
	* Constructor
	*/
	protected function __construct() {
		$maveric = CMaveric::Instance();
		$this->config   = &$maveric->config;
		$this->request  = &$maveric->request;
		$this->data     = &$maveric->data;
		$this->db       = &$maveric->db;
		$this->views    = &$maveric->views;
		$this->session  = &$maveric->session;
	}//avslutar konstruktor
	
	/**
	* Redirect to another url and store the session
	*/
	protected function RedirectTo($url) {
		$maveric = CMaveric::Instance();
		if(isset($maveric->config['debug']['db-num-queries']) && $maveric->config['debug']['db-num-queries'] && isset($maveric->db)) {
		  $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
		}    
		if(isset($maveric->config['debug']['db-queries']) && $maveric->config['debug']['db-queries'] && isset($maveric->db)) {
		  $this->session->SetFlash('database_queries', $this->db->GetQueries());
		}    
		if(isset($maveric->config['debug']['timer']) && $maveric->config['debug']['timer']) {
		  $this->session->SetFlash('timer', $maveric->timer);
		}    
		$this->session->StoreInSession();
		header('Location: ' . $this->request->CreateUrl($url));
	}//avslutar metod



}//avslutar klass