<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="<?php echo app()->language; ?>" lang="<?php echo app()->language; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?php echo app()->language; ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/plugins/fancy-type/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/main.css" />
	<?php
		if(app()->language!=app()->sourceLanguage
			&& file_exists(Yii::getPathOfAlias('webroot.css.i18n.'.app()->language).'.main.css'))
			{
				?><link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/i18n/<?php echo app()->language; ?>.main.css" /><?php
			}
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" />
	<?php
		if(app()->language!=app()->sourceLanguage
			&& file_exists(app()->theme->basePath.DS.'css'.DS.'i18n'.DS.app()->language.'.main.css'))
			{
				?><link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/i18n/<?php echo app()->language; ?>.main.css" /><?php
			}
	?>
	<link href="<?php echo wm()->get("deal.rss")->link(); ?>" rel="alternate" title="RSS" type="application/rss+xml" />
	<link rel="SHORTCUT ICON" href="<?php echo url('/').'/favicon.ico'; ?>" /> 

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class='classic-theme splash'>

<div id="wrapper">

<div id="header">
	<div class="container">		
		
		<div class="span-24 last prepend-top append-bottom">
			<div class="column" id="logo"><?php echo CHtml::link(CHtml::image(app()->theme->baseUrl.'/images/logo.png'),aUrl('/')); ?></div>
			<h2><?php 
				echo wm()->getCurrentWorklet()->title();
			?></h2>
			<div class='topmenu'><?php
				$this->worklet('base.language');
			?></div>

		</div>
	</div>

</div><!-- header -->

<div class="container" id="page">
	<?php if(app()->user->hasFlash('info')) : ?>
	<div class="span-24 last" id="info"><div class="notice">
		<?php echo app()->user->getFlash('info'); ?>
	</div></div><!-- info -->	
	<?php
	if(!app()->user->hasFlash('info.fade') || app()->user->getFlash('info.fade')!==false)
		Yii::app()->clientScript->registerScript(
			'hideEffect',
			'$("#info").animate({opacity: 1.0}, 3000).fadeOut("normal");',
			CClientScript::POS_READY
		);
	endif; ?>
	<?php echo $content; ?>
	
</div><!-- page -->

</div>

</body>
</html><?php

wm()->get('customize.theme.helper')->css('main');