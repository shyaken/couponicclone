<?php $mailer->Subject = $this->t('{deal} Failed.', array('{deal}' => $deal->name)); ?>
<strong>Hi <?php echo $recipient->name; ?>,</strong><br />
<br />
Unfortunately "<?php echo CHtml::link($deal->name, aUrl('/deal/view',array('url' => $deal->url),'http')); ?>" deal has failed.<br />
Your order has been fully refunded.<br />
<br />
<?php app()->controller->renderPartial('/system/emails/signature'); ?>
