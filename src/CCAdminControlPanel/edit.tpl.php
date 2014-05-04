<h1>Admin Control Panel Edit</h1>

<?php if(is_file($include)): ?>
	<?php include($include); ?>
<?php else: ?>
<h2>Woop!</h2>
<p>Looks like there isn't any content for this page.</p>
<p>To add content. Go into this module's <code>edit</code> folder (<code>/src/CCAdminControlPanel/edit</code>) and add a PHP file named as "<?=$variableKey?>".</p>
<p>You can then edit that file and style it of your own choosing. Check out the other files in the same folder if you need examples.</p>
<?php endif; ?>