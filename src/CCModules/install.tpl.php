<<<<<<< HEAD
<h1>Install modules</h1>
=======
<h1>Install standard modules</h1>
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5

<p>The following modules were affected by this action.</p>

<table>
	<caption>Results from installing modules.</caption>
	
	<thead>
		<tr><th>Module</th><th>Result</th></tr>
	</thead>
	
	<tbody>
<<<<<<< HEAD
		<?php print_r($modules) foreach($modules as $module): ?>
=======
		<?php foreach($modules as $module): ?>
>>>>>>> cde02307ba9fcc0eee572ce426989519b30251e5
			<tr><td><?=$module['name']?></td><td><div class='<?=$module['result'][0]?>'><?=$module['result'][1]?></div></td></tr>
		<?php endforeach; ?>
	</tbody>
</table>