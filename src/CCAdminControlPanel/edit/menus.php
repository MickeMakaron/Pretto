<?=$form->getHTML('form')?>
	<fieldset>
		<h2><?=$variableKey?></h2>
		
		<ul style="list-style-type:none"><div class="acp-edit-box">
		<?php foreach($elements['menus'] as $key => $menu) : ?> 
			<?php if(isset($menu['acpConfigElement'])): ?>
				<li><?=$menu['acpConfigElement']['label']?></li>
			<?php endif; ?>
			
			<table>
				<tbody>
						<?php foreach($menu as $key => $element) : ?> 
							<?php if($key != 'acpConfigElement'): ?>
								<tr>
									<td><?=$element['label']['acpConfigElement']->getHTML()?></td>
									<td><?=$element['url']['acpConfigElement']->getHTML()?></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
				</tbody>
			</table>
		<?php endforeach; ?>
		<?=$form['insert']->getHTML()?>
		</div></ul>

		<?=$form['save']->getHTML()?>
	</fieldset>
</form>