<?php
$this->render('workletStd',array(
	'id' => $id,
	'title' => $title,
	'content' => $content . CHtml::script('$("#uForm_DealLoc").uForm().attach();
		$("#wlt-DealLoc form input:submit").addClass("submit");'),
	'toolbar' => array(
		'left' => array('label' => $this->t('Back'), 'href' => '#', 'class' => 'back')
	),
	'page' => true,
));