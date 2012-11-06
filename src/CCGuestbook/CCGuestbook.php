<?php
/**
* A guestbook controller as an example to show off some basic controller and model-stuff.
* Index-metoden ska visa innehållet i gästboken och visar även ett formulär där det går att göra ett nytt inlägg i gästboken. 
* Formuläret postar till metoden Handler() som skapar ett nytt inlägg och add-metoden gör därefter en redirect till index-metoden igen.

* @package MavericCore
*/
class CCGuestbook extends CObject implements IController {

	// medlemsvariabler
	private $guestbookModel;
 

	/**
	* Constructor
	*/
	public function __construct() {
		parent::__construct();
		$this->guestbookModel = new CMGuestbook();
	}//avslutar konstruktor


	/**
	* Implementing interface IController. All controllers must have an index action.
	* Show a standard frontpage for the guestbook.
	*/
	public function Index() {
		$this->views->SetTitle('Maveric Guestbook Example');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
		  'entries'=>$this->guestbookModel->ReadAll(),
		  'form_action'=>$this->request->CreateUrl('', 'handler')
		));
	}//avslutar metod


	/**
	* Handle posts from the form and take appropriate action.
	*/
	public function Handler() {
		if(isset($_POST['doAdd']) && empty($_POST['email'])) {
		  $this->guestbookModel->Add(strip_tags($_POST['newEntry']));
		}
		elseif(isset($_POST['doClear'])) {
		  $this->guestbookModel->DeleteAll();
		}
		elseif(isset($_POST['doCreate'])) {
		  $this->guestbookModel->Init();
		}           
		$this->RedirectTo($this->request->CreateUrl($this->request->controller));
	}//avslutar metod
	  
	  
    
	  
}//avslutar klass