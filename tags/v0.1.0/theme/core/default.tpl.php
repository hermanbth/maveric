<!doctype html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<!-- PHP short tags används för att slippa alla echo/print-satser. -->
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$stylesheet?>">
</head>
<body>
	<div id="header">
		<?=$header?>
	</div>
	<div id="main" role="main">
		<?=$main?>
		<!-- Skriver ut information från $data-arrayen från Maveric-klassen -->
		<?=FindDebug()?>
	</div>
	<div id="footer">
		<?=$footer?>
	</div>
</body>
</html>