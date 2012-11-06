<?php
/**
* Helpers for theming, available for all themes in their template files and functions.php.
* This file is included right before the themes own functions.php
*/

/**
* Print debuginformation from the framework.
* Kan användas för att testa att det fungerar.
*/
function FindDebug() {
	// Only if debug is wanted.
	$maveric = CMaveric::Instance();   
	if(empty($maveric->config['debug'])) {
		return;
	}
	
	// Get the debug output
	$html = null;
	if(isset($maveric->config['debug']['db-num-queries']) && $maveric->config['debug']['db-num-queries'] && isset($maveric->db)) {
		$flash = $maveric->session->GetFlash('database_numQueries');
		$flash = $flash ? "$flash + " : null;
		$html .= "<p>Database made $flash" . $maveric->db->GetNumQueries() . " queries.</p>";
	}    
	if(isset($maveric->config['debug']['db-queries']) && $maveric->config['debug']['db-queries'] && isset($maveric->db)) {
		$flash = $maveric->session->GetFlash('database_queries');
		$queries = $maveric->db->GetQueries();
		if($flash) {
			$queries = array_merge($flash, $queries);
		}
		$html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
	}    
	if(isset($maveric->config['debug']['timer']) && $maveric->config['debug']['timer']) {
		$html .= "<p>Page was loaded in " . round(microtime(true) - $maveric->timer['first'], 5)*1000 . " msecs.</p>";
	}    
	if(isset($maveric->config['debug']['maveric']) && $maveric->config['debug']['maveric']) {
		$html .= "<hr><h3>Debuginformation</h3><p>The content of CLydia:</p><pre>" . htmlent(print_r($maveric, true)) . "</pre>";
	}    
	if(isset($maveric->config['debug']['session']) && $maveric->config['debug']['session']) {
		$html .= "<hr><h3>SESSION</h3><p>The content of CLydia->session:</p><pre>" . htmlent(print_r($maveric->session, true)) . "</pre>";
		$html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
	}    
	return $html;
}//avslutar metod

/**
* Get messages stored in flash-session.
*/
function get_messages_from_session() {
	$messages = CMaveric::Instance()->session->GetMessages();
	$html = null;
	if(!empty($messages)) {
		foreach($messages as $val) {
			$valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
			$class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
			$html .= "<div class='$class'>{$val['message']}</div>\n";
		}//avslutar foreach
	}//avslutar if
	return $html;
}//avslutar metod

/**
* Create a url by prepending the base_url.
*/
function createBaseUrlByUrl($url=null) {
	return CMaveric::Instance()->request->baseUrl . trim($url, '/');
}//avslutar metod

/**
 * Create a url to an internal resource.
 */
function createUrlByUrl($url=null) {
	return CMaveric::Instance()->request->CreateUrl($url);
}//avslutar metod

/**
 * Prepend the theme_url, which is the url to the current theme directory.
 */
function theme_url($url) {
  $maveric = CMaveric::Instance();
  return "{$maveric->request->baseUrl}theme/{$maveric->config['theme']['name']}/{$url}";
}//avslutar metod

/**
* Return the current url.
*/
function GetCurrentUrlFromRequest() {
	return CMaveric::Instance()->request->currentUrl;
}//avslutar metod

/**
* Render all views.
*/
function render_views() {
	return CMaveric::Instance()->views->Render();
}//avslutar metod


