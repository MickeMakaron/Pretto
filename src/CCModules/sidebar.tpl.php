<div class='box'>
	<h4>All modules</h4>
	<p>All Pretto modules.</p>
	
	<ul>
		<?php foreach($modules as $module): ?>
			<li><a href='<?=create_url("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
		<?php endforeach; ?>
	</ul>
</div>


<div class='box'>
	<h4>Pretto core</h4>
	<p>Pretto core modules.</p>
	<ul>
		<?php foreach($modules as $module): ?>
			<?php if($module['isPrettoCore']): ?>
				<li><?=$module['name']?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>


<div class='box'>
	<h4>Pretto CMF</h4>
	<p>Pretto Content Management Framework (CMF) modules.</p>
	<ul>
		<?php foreach($modules as $module): ?>
			<?php if($module['isPrettoCMF']): ?>
				<li><?=$module['name']?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>


<div class='box'>
	<h4>Models</h4>
	<p>A class is considered a model if its name starts with CM.</p>
	<ul>
		<?php foreach($modules as $module): ?>
			<?php if($module['isModel']): ?>
				<li><?=$module['name']?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>


<div class='box'>
	<h4>Controllers</h4>
	<p>Implements interface <code>IController</code>.</p>
	<ul>
		<?php foreach($modules as $module): ?>
			<?php if($module['isController']): ?>
				<li><?=$module['name']?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>


<div class='box'>
	<h4>Contains SQL</h4>
	<p>Implements interface <code>ISQL</code>.</p>
	<ul>
		<?php foreach($modules as $module): ?>
			<?php if($module['hasSQL']): ?>
				<li><?=$module['name']?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>


<div class='box'>
	<h4>More modules</h4>
	<p>Modules that does not implement any specific Pretto interface.</p>
	<ul>
		<?php foreach($modules as $module): ?>
			<?php if(!($module['isController'] || $module['isPrettoCore'] || $module['isPrettoCMF'])): ?>
				<li><?=$module['name']?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>