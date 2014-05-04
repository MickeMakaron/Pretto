<h1>Blog</h1>
<a href='<?=create_url("content/create")?>'>Add post</a>.</p>

<?php if($contents != null):?>
	<?php foreach($contents as $val):?>
		<hr>
		<a href="<?=create_url("page/view/{$val['id']}")?>"><h2><?=esc($val['title'])?></h2></a>
		<p class='smaller-text'><em>Posted on <?=$val['created']?> by <?=$val['owner']?></em></p>
		<p><?=filter_data($val['data'], $val['filter'])?></p>
		<p class='smaller-text silent'><a href='<?=create_url("content/edit/{$val['id']}")?>'>edit</a></p>
		<hr>
	<?php endforeach; ?>
<?php else:?>
	<p>No posts exists.</p>
<?php endif;?>