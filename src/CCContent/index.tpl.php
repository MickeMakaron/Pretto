<h1>Content Controller Index</h1>
<?php if($contents != null):?>
	<table>
		<caption>All pages</caption>
		
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Author</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($contents as $val):?>
				<?php if($val['type'] == 'page'): ?>
					<tr>
						<td><?=$val['id']?></td>
						<td><?=$val['title']?></td>
						<td><?=$val['owner']?></td>
						<td><a href='<?=create_url("content/edit/{$val['id']}")?>'>edit</a></td>
						<td><a href='<?=create_url("page/view/{$val['id']}")?>'>view</a></td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
	</table>

	</ul>
<?php else:?>
	<p>No content exists.</p>
<?php endif;?>

<h2>Actions</h2>
<ul>
	<li><a href='<?=create_url('content/create')?>'>Create new content</a>
</ul>
