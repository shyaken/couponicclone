<?php
$this->render('workletStd',array(
	'id' => $id,
	'title' => $title,
	'content' => '<ul>'.$content.'</ul>',
	'toolbar' => array(
		'left' => array('label' => $this->t('Back'), 'href' => '#', 'class' => 'back')
	),
));