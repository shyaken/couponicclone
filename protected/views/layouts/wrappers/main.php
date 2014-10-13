<?php $isAdmin = wm()->get('admin.helper')->layout(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="<?php echo app()->language; ?>" lang="<?php echo app()->language; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="language" content="<?php echo app()->language; ?>" />
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/blueprint/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/blueprint/plugins/fancy-type/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/main.css" />
	<?php
		if(app()->language!=app()->sourceLanguage
			&& file_exists(Yii::getPathOfAlias('webroot.css.'.app()->language).'.main.css'))
			{
				?><link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/<?php echo app()->language; ?>.main.css" /><?php
			}
	?>
	<link href="<?php echo wm()->get("deal.rss")->link(); ?>" rel="alternate" title="RSS" type="application/rss+xml" />
	<link rel="SHORTCUT ICON" href="<?php echo url('/').'/favicon.ico'; ?>" /> 

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="container" id="page">
	<?php
	if(!$isAdmin)
	{
		$this->worklet('location.select');
		$this->worklet('deal.subscribe');
	}
	?>
	
	<div class="span-24 last prepend-top" id="header">
		<h2 class='column'><?php echo app()->name; ?></h2>
		<div class='topmenu'><?php
			$this->worklet('base.language');
			if(!$isAdmin)
				$this->worklet('base.topMenu');
		?></div>
	</div><!-- header -->
	
	<hr />
	
	<div class="span-24 last" id="mainmenu">
		<?php
		if($isAdmin)
			$this->worklet('admin.menu');
		else
		{
			?><div class='column'><?php	$this->worklet('base.menu'); ?></div>
			<div><?php $this->worklet('user.menu'); ?></div><?php
		}
		?>
	</div><!-- mainmenu -->
	
	<hr />
	
	<?php if(count($this->breadcrumbs)):
	$this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
		'homeLink'=> CHtml::link($this->t('Home'),wm()->get('admin.helper')->homeLink()),
	));
	?><hr /><?php
	endif; ?><!-- breadcrumbs -->
	
	<?php if(app()->user->hasFlash('info')) : ?>
	<div class="span-24 last" id="info"><div class="notice">
		<?php echo app()->user->getFlash('info'); ?>
	</div></div><!-- info -->	
	<?php
	Yii::app()->clientScript->registerScript(
		'hideEffect',
		'$("#info").animate({opacity: 1.0}, 3000).fadeOut("normal");',
		CClientScript::POS_READY
	);
	endif; ?>
	
	<?php echo $content; ?>
	
	<hr />
	
	<div class="span-24 last" id="footer">
		<div class='container'>
			<?php if(!$isAdmin) { ?>
			<div class='span-5 append-1'>
				<?php
					$this->worklet('base.followMenu');
					$this->worklet('deal.stats');
				?>
			</div>
			<div class='span-18 last'>
				<div id='footermenu'><?php $this->worklet('base.footerMenu'); ?></div>
				<hr />
				<?php
					echo $this->t('Copyright &copy; {year} by {name}.', array(
						'{year}' => date('Y'),
						'{name}' => app()->name,
					));
				?><br/><?php
					echo $this->t('All Rights Reserved.');
				?><br/>
				<?php echo param('poweredBy'); ?>
			</div>
			<?php } else { ?>
			<div class='txt-center'>
				<?php
					echo $this->t('Copyright &copy; {year} by {name}.', array(
						'{year}' => date('Y'),
						'{name}' => app()->name,
					));
				?><br/><?php
					echo $this->t('All Rights Reserved.');
				?><br/>
				<?php echo param('poweredBy'); ?>
			</div>
			<?php } ?>
		</div>
	</div><!-- footer -->
	
</div><!-- page -->
<?php
if(isset($this->clips['outside']))
	echo $this->clips['outside'];
?>
</body>
</html>