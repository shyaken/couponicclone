<?php
$this->render('workletStd',array(
	'id' => $id,
	'title' => $title,
	'content' => $content,
	'toolbar' => array(
		'left' => array('label' => $this->t('Back'), 'href' => url('/'), 'class' => 'back')
	),
	'page' => true,
));
cs()->registerScript(__FILE__,'jQuery("#wlt-PaymentCart-grid .uDialog").removeClass("uDialog").attr({"rel":""});');
cs()->registerScriptFile(asma()->publish(Yii::getPathOfAlias('deal.js.deal').'.loc.js'));