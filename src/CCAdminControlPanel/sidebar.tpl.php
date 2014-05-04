<ul>
<?php foreach($vars as $key => $var): ?>
	<li><a href='<?=create_url('acp/edit/'.$key) ?>'><?=$key?></a></li>
<?php endforeach; ?>
</ul>