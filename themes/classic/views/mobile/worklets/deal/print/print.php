<h1>#<?php echo $this->coupon()->couponId(); ?></h1>
<h1><?php echo $this->coupon()->deal->name; ?></h1>
<div class='box'>
	<h5><?php echo $this->t('Recipients').':'; ?></h5>
	<?php echo $this->coupon()->user->getName(true); ?>
	<h5><?php echo $this->t('Expires On').':'; ?></h5>
	<?php echo $this->coupon()->deal->expire
		? app()->getDateFormatter()->formatDateTime(
			utime($this->coupon()->deal->expire,false), "medium")
		: $this->t('Never'); ?>
	<h5><?php echo $this->t('Fine Print').':'; ?></h5>
	<?php echo app()->format->ntext($this->coupon()->deal->finePrint); ?>
</div>
<div class='box'>
	<h5><?php echo $this->t('The Company').':'; ?></h5><?php
		echo $this->coupon()->deal->company->name.'<br />';
		if($this->coupon()->deal->company->phone)
			echo $this->t('Phone').': '.$this->coupon()->deal->company->phone.'<br />';
		echo $this->coupon()->deal->company->website;
	?><h5><?php echo $this->t('Redeemable After').':'; ?></h5>
	<?php echo $this->coupon()->deal->redeemStart
		? app()->getDateFormatter()->formatDateTime(
			utime($this->coupon()->deal->redeemStart,false), "medium")
		: $this->t('Anytime'); ?>
	
	<?php if(count($this->coupon()->deal->redeemLocs)): ?>
	<h5><?php echo $this->t('Redeem At').':'; ?></h5><?php
	foreach($this->coupon()->deal->redeemLocs as $l)
	{
		?><address><?php
			echo wm()->get('location.helper')->locationAsText($l->loc,$l->address,$l->zipCode);
		?></address><?php
	}
	endif;
	?>
</div>
<div class='box txt-center'><?php
	echo CHtml::image(url('/deal/barcode', array('barcode' => $this->coupon()->redemptionCode)));
?></div>