<?php $mailer->Subject = $this->t('Order for {deal} received.', array('{deal}' => $deal->name)); ?>
<strong>Hi <?php echo $recipient->name; ?>,</strong><br />
<br />
Thank you for placing your order for the deal "<?php echo CHtml::link($deal->name, aUrl('/deal/view',array('url' => $deal->url),'http')); ?>".<br />
As soon as a minimum required amount of people have joined the deal and the deal has ended, we will send you a notice which will allow you to print out your coupon and redeem it.<br />
<br />
In some cases, the minimum amount of people required to join the deal is not reached. If this is the case then you will not be charged and the deal will end with no one being able to enjoy the deeply discounted deal.<br />
<br />
<?php app()->controller->renderPartial('/system/emails/signature'); ?>