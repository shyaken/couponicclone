<h4><?php echo $this->t('Instructions'); ?></h4>
<ul>
	<li><?php echo $this->t('Create theme package using the above form'); ?></li>
	<li><?php echo $this->t('Unzip'); ?></li>
	<li><?php echo $this->t('Upload to {path}', array('{path}' => Yii::getPathOfAlias('webroot'))); ?></li>
	<li><?php echo $this->t('To switch your site to this new theme edit this file: {path}.', array(
		'{path}' => app()->basePath.DS.'config'.DS.'public'.DS.'instance.php'
	)); ?><br />
	<?php echo $this->t('Just change \'themes\' => \'classic\' to \'theme\' => \'{THEME_ID}\''); ?><br />
	<?php echo $this->t('{THEME_ID} is your theme name from the from above in a lower case.'); ?><br />
	<?php echo $this->t('Ex.').':'; ?><div class='box'><?php
	$str = <<<EOD
<?php
 return array (
  'name' => 'Couponic',
  ...
  'theme' => '[s]mynewtheme[/s]',
);
EOD;
	$str = highlight_string($str,true);
	echo strtr($str,array('[s]'=>'<strong>','[/s]'=>'</strong>'));
	?></div>
	</li>
	<li><?php
	echo $this->t('Please note that {script} uses templates "inheritance" technique.',array(
		'{script}' => app()->getTitle()
	)).' ';
	echo $this->t('So you can safely delete templates which you don\'t want to edit from your theme and script will automatically use default ones in this case.');
	?></li>
</ul>