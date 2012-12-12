<?php
/**
 * Controller for development and testing purpose, helpful methods for the developer.
 * 
 * @package LydiaCore
 */
class CCDeveloper implements IController {

	/**
	* Implementing interface IController. All controllers must have an index action.
	*/
	public function Index() {  
		$this->Menu();
	}//avslutar metod


	/**
	* Create a list of links in the supported ways.
	*/
	public function Links() {  
		$this->Menu();

		$maveric = CMaveric::Instance();

		$url = 'developer/links';
		$current      = $maveric->GetRequest()->CreateUrl($url);

		$maveric->GetRequest()->SetCleanUrl(false);
		$maveric->GetRequest()->SetQuerystringUrl(false);    
		$default      = $maveric->GetRequest()->CreateUrl($url);

		$maveric->GetRequest()->SetCleanUrl(true);
		$clean        = $maveric->GetRequest()->CreateUrl($url);    

		$maveric->GetRequest()->SetCleanUrl(false);
		$maveric->GetRequest()->SetQuerystringUrl(true);    
		$querystring  = $maveric->GetRequest()->CreateUrl($url);

		$data = $maveric->GetData();
		If(isset($data['main'])){
			$dataMainCurrent = $data['main'];
		}else{
			$dataMainCurrent = NULL; 
		}	
		$maveric->SetData('main', $dataMainCurrent . <<<EOD
<h2>CRequest::CreateUrl()</h2>
<p>Here is a list of urls created using above method with various settings. All links should lead to
this same page.</p>
<ul>
<li><a href='$current'>This is the current setting</a>
<li><a href='$default'>This would be the default url</a>
<li><a href='$clean'>This should be a clean url</a>
<li><a href='$querystring'>This should be a querystring like url</a>
</ul>
<p>Enables various and flexible url-strategy.</p>
EOD
);

	}//avslutar metod


   /**
   * Create a method that shows the menu, same for all methods
   */
  private function Menu() {  
    $maveric = CMaveric::Instance();
    $menu = array('developer', 'developer/index', 'developer/links');
    
    $html = null;
    foreach($menu as $val) {
      $html .= "<li><a href='" . $maveric->GetRequest()->CreateUrl($val) . "'>$val</a>";  
    }
    
    $maveric->SetData('title', "The Developer Controller");
    $data = $maveric->GetData();
	If(isset($data['main'])){
		$dataMainCurrent = $data['main'];
	}else{
		$dataMainCurrent = NULL; 
	}	
	$maveric->SetData('main', $dataMainCurrent . <<<EOD
<h1>The Developer Controller</h1>
<p>This is what you can do for now:</p>
<ul>
$html
</ul>
EOD
);
	} //avslutar metod
  
}//avslutar klassen