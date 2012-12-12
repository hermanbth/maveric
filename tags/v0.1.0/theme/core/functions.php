<?php
/**
* Helpers for the template file.
*
* Innehåller kod som ger mer möjligheter för tema.
* Tema? Ett koncept för stylesheets, bilder, funktioner och template-filer. Meningen med tema är att det ska gå att styla webbplatsen 
* på flexibelt vis, t ex ändra färger i stylesheeten (tänk Wordpress). Men om vill ändra en hårdkodad del av webbplatsen, t ex footer, så krävs ändring
* i $data-arrayen. Sådan ändring kan ske via databas men ibland krävs ändring i template-filer.	
*/

// värden (hårdkodade) läggs in i data-arrayen, så att det finns något att skriva ut.
$maveric->SetData('header', '<h1>Header: Maveric</h1>');
$maveric->SetData('footer', '<p>Footer: &copy; Maveric</p>
	<p>Verktyg f&ouml;r validering: 
		<a href="http://validator.w3.org/check/referer">HTML5</a> 
		<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>
		<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS3</a>
		<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">Unicorn (HTML och CSS samtidigt)</a>
		<a href="http://validator.w3.org/i18n-checker/check?uri={$maveric->request->getCurrentUrl()}">i18n</a>
		<a href="http://validator.w3.org/checklink?uri={$maveric->request->getCurrentUrl()}">Links</a>
	</p>

	<p>&Ouml;vriga verktyg: 
		<a href="/~jche10/htmlphp/kmom02/viewsource.php">Visa k&auml;llkod</a>
	</p>

	<p>Manualer: 
		<a href="http://www.w3.org/2009/cheatsheet/">Cheatsheet</a>
		<a href="http://dev.w3.org/html5/spec/spec.html">HTML5</a> 
		<a href="http://www.w3.org/TR/CSS2/">CSS2</a> 
		<a href="http://www.w3.org/Style/CSS/current-work">CSS3</a> 
		<a href="http://php.net/manual/en/index.php">PHP</a> 
	</p>
');


/**
* Print debuginformation from the framework.
* Kan användas för att testa att det fungerar.
*/
function FindDebug() {
	$maveric = CMaveric::Instance();
	$html = "<h2>Debuginformation</h2><hr><p>The content of the config array:</p><pre>" . htmlentities(print_r($maveric->GetConfig(), true)) . "</pre>";
	$html .= "<hr><p>The content of the data array:</p><pre>" . htmlentities(print_r($maveric->GetData(), true)) . "</pre>";
	$html .= "<hr><p>The content of the request array:</p><pre>" . htmlentities(print_r($maveric->GetRequest(), true)) . "</pre>";
	return $html;
}//avslutar metod