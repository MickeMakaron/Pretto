<h1>Theme</h1>
<p>Click on the links to render specific regions</p>
<p><a href='<?=create_url('theme')?>'>Go back</a></p>
<p><a href='<?=create_url('theme/someRegions')?>'>Clear</a></p>
<ul>
	<?php foreach($regions as $region): ?>
		<li><a href='<?=argument_unique($region)?>'><?=$region?></a>
	<?php endforeach; ?>
</ul>