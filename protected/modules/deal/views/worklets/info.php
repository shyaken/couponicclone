<div class='clearfix'>
	<div class='span-11 colborder'>
		<?php
			echo $this->deal->description;
			if(count($this->deal->reviews))
			{
				?><h3><?php echo $this->t('Reviews'); ?></h3><?php
				foreach($this->deal->reviews as $review) {
					?><blockquote><?php echo app()->format->ntext($review->review); ?>
					<span class='signature'>&ndash; <?php echo CHtml::link($review->name,$review->website,array('target'=>'_blank')); ?></span>
					</blockquote><?php
				}
			}		
	?></div>
	<div class='span-5 last'>
		<h3><?php echo $this->t('The Company'); ?></h3>
		<strong><?php echo $this->deal->company->name; ?></strong>
		<?php if($this->deal->company->phone) { ?><p><?php
			echo $this->t('Phone').': '.$this->deal->company->phone; ?></p><?php } ?>
		<address><?php
			echo wm()->get('location.helper')->locationAsText(
				$this->deal->company->loc,
				$this->deal->company->address,
				$this->deal->company->zipCode
			);
		?></address>
		<?php echo $this->deal->company->website
				? CHtml::link($this->t('website'),$this->deal->company->website,array('target'=>'_blank'))
				: ''; ?>
		<?php
		if(count($this->deal->redeemLocs)):
		$country = '';
		$address = array();
		foreach($this->deal->redeemLocs as $l)
		{
			$country = $l->loc->country;
			$address[] = array(
				'address' => wm()->get('location.helper')->locationAsText($l->loc,
					$l->address,$l->zipCode,', '),
				'lon' => $l->lon,
				'lat' => $l->lat
			);
		}
		app()->controller->worklet('base.googleMap',array(
			'country' => $l->loc->country,
			'address' => $address,
		));
		foreach($this->deal->redeemLocs as $l)
		{
			?><address><?php
				echo wm()->get('location.helper')->locationAsText($l->loc,$l->address,$l->zipCode);
				?><br /><?php
				$addr = wm()->get('location.helper')->locationAsText($l->loc,
					$l->address,$l->zipCode,', ');
				$addr = isset($l->lat) && isset($l->lon)
					? $l->lat.', '.$l->lon.' ('.$addr.')'
					: $addr;
				echo CHtml::link($this->t('Map It!'), 'http://maps.google.com/?q='.urlencode($addr),
					array('target' => '_blank'));
			?></address><?php
		}
		endif;
		?>
	</div>
</div>