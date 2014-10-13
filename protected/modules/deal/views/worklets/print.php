<table>
<tr>
	<td>
		<h1><?php echo app()->name; ?></h1>
	</td><td class='txt-right'>	
		<h1>#<?php echo $this->coupon()->couponId(); ?></h1>
	</td>
</tr>
<tr><td colspan="2"><h3><?php echo $this->coupon()->deal->name; ?></h3></td></tr>
<tr>
	<td style="width:50%; vertical-align: top">
		<h5><?php echo $this->t('Recipients').':'; ?></h5>
		<?php echo $this->coupon()->user->getName(true); ?>
		<h5><?php echo $this->t('Expires On').':'; ?></h5>
		<?php echo $this->coupon()->deal->expire
			? app()->getDateFormatter()->formatDateTime(
				utime($this->coupon()->deal->expire,false), "medium")
			: $this->t('Never'); ?>
		<h5><?php echo $this->t('Fine Print').':'; ?></h5>
		<?php echo app()->format->ntext($this->coupon()->deal->finePrint); ?>
	</td>
	<td style='vertical-align: top'>
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
		$country = '';
		$address = array();
		foreach($this->coupon()->deal->redeemLocs as $l)
		{
			if(!$this->coupon()->redeemLocationId || 
				$this->coupon()->redeemLocationId == $l->id){
				$country = $l->loc->country;
				$address[] = array(
					'address' => wm()->get('location.helper')->locationAsText($l->loc,
						$l->address,$l->zipCode,', '),
					'lon' => $l->lon,
					'lat' => $l->lat,
				);
				?><address><?php
					echo wm()->get('location.helper')->locationAsText($l->loc,$l->address,$l->zipCode);
				?></address><?php
			}
		}
		endif;
	?>
                <h5><?php echo $this->t('Redemption Code'); ?></h5>
                <?php echo $this->coupon()->redemptionCode; ?>
        </td>
</tr><?php
	if(count($this->coupon()->deal->redeemLocs)) {
		?><tr><td colspan="2" class='txt-center'><hr /><?php
		app()->controller->worklet('base.googleMap',
			array('country' => $country, 'address' => $address, 'printView' => true));
		?></td></tr><?php
	}
?><tr><td colspan='2' class='txt-center'><?php
	echo CHtml::image(url('/deal/barcode', array('barcode' => $this->coupon()->redemptionCode)));
?></td></tr>	
</table>