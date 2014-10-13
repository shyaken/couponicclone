<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/screen.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/print.css" type="text/css" media="screen, print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/blueprint/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/main.css" type="text/css" />
	
	<?php
		if(app()->language!=app()->sourceLanguage
			&& file_exists(Yii::getPathOfAlias('webroot.css.'.app()->language).'.main.css'))
			{
				?><link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/<?php echo app()->language; ?>.main.css" type="text/css" /><?php
			}
	?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class='classic-theme'>
<div class="container">
	<?php if(isset($this->clips['content'])) echo $this->clips['content']; ?>
</div>
</body>
</html>