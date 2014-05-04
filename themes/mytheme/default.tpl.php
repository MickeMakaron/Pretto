<!doctype html>
<html lang="sv">
	<head>
		<meta charset="utf-8">
		<title><?=$title?></title>
		<link rel="stylesheet" href="<?=$stylesheet?>">
	</head>

	<body>
		<div id="header">
			<?=@$header?>
			
			<div id='login-menu'>
				<?=login_menu()?>
			</div>
		</div>
		
		<div id="main" role="main">
			<?=get_messages_from_session()?>
<<<<<<< HEAD
			<?=get_messages()?>
=======
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
			<?=@$main?>
			<?=render_views()?>
			<?=get_debug()?>
		</div>
	
		<div id="footer">
			<?=@$footer?>
		</div>
	</body>
</html>