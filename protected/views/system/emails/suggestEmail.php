<?php $mailer->Subject = $this->t('{site}: Business Suggestion', array('{site}' => $site)); ?>
Business Name: <?php echo $model->name; ?><br />
Business Website: <?php echo CHtml::link($model->website, $model->website); ?><br />
Location: <?php echo wm()->get('location.helper')->locationAsText(
	wm()->get('location.helper')->locationToData($model->location,true),false,false,' '); ?><br />
<br />
Review:<br />
<br />
<?php echo nl2br($model->review); ?>