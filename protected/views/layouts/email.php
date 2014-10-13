<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<style type="text/css">
<?php if(app()->locale->txtDirection == 'r2l') { ?>
body {direction: rtl; text-align: right;}
<?php } ?> 
</style>
</head>
<body>
<?php echo $content; ?>
<hr />
<div><?php
	echo CHtml::link(t('Contact Us'), aUrl('/base/contact',array(),'http'));
	if(isset($subscription) && $subscription)
		echo ' | '.CHtml::link(t('Unsubscribe'), aUrl('/subscription/delete', array('h' => $subscription), 'http'));
?></div>
</body>
</html>