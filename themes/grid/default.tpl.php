<!doctype html>
<html lang="sv">
	<head>
		<meta charset="utf-8">
		<title><?=$title?></title>
		<link rel='shortcut icon' href='<?=theme_url($favicon)?>'/>
		<link rel='stylesheet' href='<?=theme_url($stylesheet)?>'/>
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
			<?=get_messages()?>
			<?=@$main?>
			<?=render_views()?>
			<?=get_debug()?>
		</div>
	
		<div id="footer">
			<?=@$footer?>
		</div>
	</body>
</html>