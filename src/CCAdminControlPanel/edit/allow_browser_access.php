<?=$form->getHTML('form')?>
	<fieldset>
		<h2><?=$variableKey?></h2>
		<ul style="list-style-type:none">
			<p>Do you want to allow modifying config variables through the site?</p>
			
			<p style="float:left;">Then check this box!</p>
			<?php $elements['allow_browser_access']['acpConfigElement']['label'] = null ?>
			<?=$elements['allow_browser_access']['acpConfigElement']->getHTML()?>
			
			<p>Unchecking the box just means that Pretto will only load settings from the config file</p>
			<p>If checked, Pretto will load settings from the database IN ADDITION to config settings.</p>
			<p>NOTE: For this particular setting, the config file has priority. In the config it is enabled by default.</p>
		</ul>

		<?=$form['save']->getHTML()?>
	</fieldset>
</form>