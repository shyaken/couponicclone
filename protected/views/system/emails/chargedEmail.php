<?php $mailer->Subject = $this->t('{deal} Tipped!', array('{deal}' => $deal->name)); ?>
<strong>Hi <?php echo $recipient->name; ?>,</strong><br />
<br />
"<?php echo CHtml::link($deal->name, aUrl('/deal/view',array('url' => $deal->url),'http')); ?>" has successfully "tipped"!<br />
You can now login to your account and print your coupon.<br />
<br />
<?php app()->controller->renderPartial('/system/emails/signature'); ?>