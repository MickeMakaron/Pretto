<ul>
	<?php foreach($variables as $key1 => $var): ?>
		<li><?=$key1?></li>
		
		<ul>
			<?php foreach($var as $key2 => $value): ?>
				<li><?=$key2?> => <?=$value?></li>
			<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
</ul>