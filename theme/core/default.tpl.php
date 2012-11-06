<!doctype html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<!-- PHP short tags används för att slippa alla echo/print-satser. -->
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$stylesheet?>" />
</head>
<body>
	<div id='wrap-header'>
		<div id="header">
			<?=$header?>
		</div>
	</div>	
	<div id='wrap-main'>
		<div id='main' role='main'>
			<?=get_messages_from_session()?>
			<?=@$main?>
			<?=render_views()?>
		</div>
	</div>
	<div id='wrap-footer'>
		<div id="footer">
			<?=$footer?>
			<!-- Skriver ut information från $data-arrayen från Maveric-klassen -->
			<?=FindDebug()?>
		</div>
	</div>	
</body>
</html>