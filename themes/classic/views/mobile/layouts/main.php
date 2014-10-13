<?php $this->beginContent('/layouts/wrappers/'.(isset($wrapper)?$wrapper:'main')); ?>
<div class='page current'>

<?php if(app()->user->hasFlash('info')) : ?>
<div id="info"><div class="notice">
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

<?php
	echo $this->clips['content']; echo $content;
	$this->worklet('base.language');
?>
<div class='switchLink'><?php
echo CHtml::link($this->t('Switch to Full Site'), url('base/setting', array(
	'name' => 'ignoreMobile',
	'value' => '1',
	'next' => '1'
)));
?></div>
</div>
<?php
foreach($this->clips as $k=>$v)
	if($k != 'content')
		echo $v;
		
$this->worklet('location.select');
$this->endContent();