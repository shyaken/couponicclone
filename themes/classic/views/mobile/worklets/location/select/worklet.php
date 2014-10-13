<?php
$this->render('workletStd',array(
	'id' => $id,
	'title' => $title,
	'content' => $content,
	'toolbar' => array(
		'left' => array('label' => $this->t('Back'), 'href' => '#', 'class' => 'back')
	),
));
?>