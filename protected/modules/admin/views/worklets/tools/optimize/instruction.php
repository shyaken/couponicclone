<h4><?php echo $this->t('Instructions'); ?></h4>
<ul>
	<li><?php echo $this->t('Run optimizer using the form above'); ?></li>
	<li><?php echo $this->t('Unzip'); ?></li>
	<li><?php echo $this->t('Upload all files to {path} (overwrite existing ones)',array(
		'{path}' => Yii::getPathOfAlias('webroot')
	)); ?></li>
</ul>