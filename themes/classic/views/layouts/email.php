<?php $h = wm()->get('customize.theme.helper'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo app()->language; ?>" lang="<?php echo app()->language; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="<?php echo app()->language; ?>" />
<style type="text/css">
a, a:active {color: <?php echo $h->color('link'); ?>; text-decoration: none;}
a:hover, a:focus {color: <?php echo $h->color('link.hover'); ?>; text-decoration: underline}
</style>
</head>
<body style='<?php if(app()->locale->txtDirection == 'r2l') { ?>direction: rtl; text-align: right;<?php } ?>font-size:9pt;color:#222;background:#fff;font-family:"Helvetica Neue", Arial, Helvetica, sans-serif;'>
<table style='margin: 0; padding: 0; border-collapse: collapse; margin: 10px auto; width:700px; border: 5px solid <?php echo $h->color('email.border'); ?>;' align='center'>
	<tr><td style='padding: 0; margin: 0'>
		<div style='background-color: <?php echo $h->color('body.bcg'); ?>;
			background-image: url(<?php echo app()->request->getHostInfo('http').app()->theme->baseUrl; ?>/images/bcgEmail.png);
			background-position: top left;
			background-repeat: repeat-x;'>
				<div style='padding: 10px; background-color: <?php echo $h->color('header.bcg'); ?>; text-align: center'>
					<?php echo CHtml::link(CHtml::image(app()->request->getHostInfo('http').app()->theme->baseUrl.'/images/logoEmail.png', app()->name, array('style' => 'border:none')),aUrl('/',array(),'http')); ?>
				</div>
				<div style='padding: 10px; border-top: 5px solid <?php echo $h->color('main.menu.bcg'); ?>;'>
					<div style='padding: 20px; background-color: #fff'>
						<?php echo $content; ?>
					</div>
				</div>
		</div>
	</td></tr>
</table>
<div style='text-align: center;'><?php
	echo CHtml::link(t('Contact Us'), aUrl('/base/contact',array(),'http'));
	if(isset($subscription) && $subscription)
		echo ' | '.CHtml::link(t('Unsubscribe'), aUrl('/subscription/delete', array('h' => $subscription), 'http'));
?></div>
</body>
</html>