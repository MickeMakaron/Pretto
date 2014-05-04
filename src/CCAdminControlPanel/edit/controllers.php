

<?=$form->getHTML('form')?>
	<fieldset>
		<h2><?=$variableKey?></h2>
		<ul style="list-style-type:none"><div class="acp-edit-box">
		<table>
			<tbody>
		<?php foreach($elements['controllers'] as $key => $controller) : ?> 
			<?php if($controller['class']['acpConfigElement']['value'] != 'CCAdminControlPanel'): ?>
				<tr>
					<td><?=$controller['class']['acpConfigElement']['value']?></td>
					<td><?=$controller['enabled']['acpConfigElement']->getHTML()?></td>
				</tr>	
			<?php endif; ?>
		<?php endforeach; ?>
			</tbody>
		</table>
		</div></ul>

		<?=$form['save']->getHTML()?>
	</fieldset>
</form>