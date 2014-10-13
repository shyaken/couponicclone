<?php
if($this->missingDealsLocation)
	$this->render('missingDealsLocation');
$this->render('form');
?>
<form><div class='buttons'>
	<?php echo CHtml::link($this->t('Today\'s Deal'), url('/')); ?>
	|
	<?php echo CHtml::link($this->t('Sign In'),url('/user/login')); ?>
	|
	<?php echo CHtml::link($this->t('Privacy Policy'),url('/base/page',array('view' => 'privacy')),array(
		'target' => '_blank'
	)); ?>
</div></form>