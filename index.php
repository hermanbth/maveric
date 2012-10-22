<?php
// print("detta är sidan index.php" . "<br /><br />");

//
// PHASE: BOOTSTRAP
// start/initiering där grunder sätts, definieras, laddas. Grunderna behövs i varje förfrågan som kommer in till filen.
// väcker till liv.
//

// bestämmer sökvägen till installations-katalogen
define('MAVERIC_INSTALL_PATH', dirname(__FILE__));
// bestämmer sökvägen till site-katalogen (kallas ibland applikations-katalogen). 
// I denna katalog lägger utvecklaren all sin egen kod som kompletterar den befintliga standardkode i ramverket.
// Alltså är detta katalogen för själva webbplatsen.
define('MAVERIC_SITE_PATH', MAVERIC_INSTALL_PATH . '/site');

// koden som samlats i bootstrap.php-filen körs 
require(MAVERIC_INSTALL_PATH.'/src/CMaveric/bootstrap.php');

// skapar ett objekt av Maveric-klassen. Detta blir ramverkets kärna. Objektet kan nås var som helst ifrån (objektet är globalt).   
// Objektet kommer att användas när man vill göra någonting.
$maveric = CMaveric::Instance();

/*
print("SERVER-arrayen: ");
print_r($_SERVER);   
print("<br />");
print("<br />");
print("<br />");
*/

//
// PHASE: FRONTCONTROLLER ROUTE
// frontController->route sköter förfrågan och avgör vilken kontroller och metod som ska användas. Sen görs allt i kontrollern som valts.
// 

// Förfrågan (url-läken) tolkas, vilken kontroller och metod som ska anropas bestämms, anropas, kontrollern hanterar och svarar på frågan.
// Kontrollern anropar modeller som krävs (t ex kan info hämtas/sparas i databasen). Allt innehåll sparas så att det sedan kan användas av
// themeEngine->render.
$maveric->FrontControllerRoute();




//
// PHASE: THEME ENGINE RENDER
// themeEngine->render skapar resultatet på förfrågan, alltså själva webbsidan. Innehållet förs över till HTML-filer mha template-filer. 
// Resultatet skapas mha innehållet från kontrollern.
// Allting som finns i $data-arrayen (medlemmen $data i CMaveric) ska skrivas ut.

$maveric->ThemeEngineRender();