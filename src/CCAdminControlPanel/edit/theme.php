<?=$form->getHTML('form')?>
	<fieldset>
		<h2><?=$variableKey?></h2>
		<?php $elements = $elements['theme']['data']; ?>
		
		<?=$elements['header']['acpConfigElement']->getHTML()?>
		<?=$elements['slogan']['acpConfigElement']->getHTML()?>
		<?=$elements['favicon']['acpConfigElement']->getHTML()?>
		<?=$elements['logo']['acpConfigElement']->getHTML()?>
		<?php //$elements['footer']['acpConfigElement']['value'] = htmlspecialchars($elements['footer']['acpConfigElement']['value'], ENT_QUOTES); ?>
		<?php $elements['footer']['acpConfigElement']['type'] = 'textarea'; ?>
		<?=$elements['footer']['acpConfigElement']->getHTML()?>
		<?=$form['save']->getHTML()?>
	</fieldset>
</form>