<?php $mailer->Subject = $this->t('{site}: New Partnership Request', array('{site}' => $site)); ?>

Business Name: <?php echo $model->companyName; ?><br />
Contact Person Name: <?php echo $model->firstName.' '.$model->lastName; ?><br />
Email Address: <?php echo $model->email; ?><br />
Location: <?php echo wm()->get('location.helper')->locationAsText(
	wm()->get('location.helper')->locationToData($model->location,true),false,false,' '); ?><br />
Phone Number: <?php echo $model->phone; ?><br />
Website: <?php echo CHtml::link($model->website, $model->website); ?><br />
<br />
Reviews:<br />
<?php echo nl2br($model->reviews); ?>
<br />
About:<br />
<?php echo nl2br($model->about); ?>