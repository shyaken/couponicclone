<?php
$this->render('workletStd',array(
	'id' => $id,
	'title' => $title,
	'content' => $content,
));

app()->controller->beginClip('dealInfo');
$this->render('workletStd',array(
	'id' => 'dealInfo',
	'title' => $title,
	'content' => $this->render('dealInfo',null,true),
	'toolbar' => array(
		'left' => array('label' => $this->t('Back'), 'href' => '#', 'class' => 'back')
	),
	'page' => true,
));
app()->controller->endClip();