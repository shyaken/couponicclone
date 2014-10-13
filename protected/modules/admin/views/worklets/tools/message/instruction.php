<h4><?php echo $this->t('Instructions'); ?></h4>
<ul>
	<li><?php
		echo $this->t('Create language package using the above form.');
		echo ' ';
		echo $this->t('It will include the main language file in protected/messages/{LANGUAGE_ID}/uniprogy.php and several other files that need to be translated.');
	?></li>
	<li><?php echo $this->t('Translate it'); ?></li>
	<li><?php echo $this->t('Upload to your server'); ?></li>
	<li><?php
	echo $this->t('To switch your site to this new language edit this file: {path}.', array(
		'{path}' => app()->basePath.DS.'config'.DS.'public'.DS.'instance.php'
	));
	?><br />
	<?php echo $this->t('Insert').': \'language\' => \'{LANGUAGE_ID}\''; ?><br />
	<?php echo $this->t('After').': return array ('; ?><br />
	<?php echo $this->t('Ex.').':'; ?><div class='box'><?php
	$str = <<<EOD
<?php
 return array (
  'language' => '[s]fr[/s]',
  'name' => 'Couponic',
  ...
  'theme' => 'classic',
);
EOD;
	$str = highlight_string($str,true);
	echo strtr($str,array('[s]'=>'<strong>','[/s]'=>'</strong>'));
	?></div>
	</li>
</ul>