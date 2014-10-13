<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1 user-scalable=no,width = 320" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="default" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/screen.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/main.css" type="text/css" />
	<?php
		if(app()->language!=app()->sourceLanguage
			&& file_exists(Yii::getPathOfAlias('webroot.css.'.app()->language).'.main.css'))
			{
				?><link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/<?php echo app()->language; ?>.main.css" type="text/css" /><?php
			}
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/mobile.css" type="text/css" />
	<?php
		if(app()->language!=app()->sourceLanguage
			&& file_exists(app()->theme->basePath.DS.'css'.DS.app()->language.'.mobile.css'))
			{
				?><link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/<?php echo app()->language; ?>.mobile.css" type="text/css" /><?php
			}
	?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php
	echo CHtml::script('
		var jQT = new $.jQTouch({
			statusBar: "black",
			slideSelector: ".slide",
			formSelector: false,
			useFastTouch: false
		});
		$(document).ready(function(){
			$("a").each(function(){
				if($(this).attr("href") && $(this).attr("href").indexOf("#")!==0)
					$(this).attr({rel:"external"});
			});
			$("form input:submit").addClass("submit");
		});
	');
	?>
</head>
<body class='classic-theme-mobile'><?php echo $content; ?></body>
</html><?php
foreach(cs()->scriptMap as $k=>$v)
	if(strpos($v,'all.css'))
		cs()->scriptMap[$k] = false;
cs()->scriptMap['all.css'] = false;
cs()->scriptMap['location.select.css'] = false;
wm()->get('customize.theme.helper')->css('mobile');