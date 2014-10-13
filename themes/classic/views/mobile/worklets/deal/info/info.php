<h3><?php echo $this->t('The Company'); ?></h3>
<div class='box'>
<strong><?php echo $this->deal->company->name; ?></strong><br />
<?php
	if($this->deal->company->phone)
		echo $this->t('Phone').': '.$this->deal->company->phone; ?><br /><?php
	echo wm()->get('location.helper')->locationAsText(
		$this->deal->company->loc,
		$this->deal->company->address,
		$this->deal->company->zipCode
	);
	if($this->deal->company->website)
	{
		?><br /><?php
		echo CHtml::link($this->deal->company->website,
			$this->deal->company->website,array('target'=>'_blank'));
	}
?></div>
<ul>
	<li class='arrow'><a class='slide' href='#dealInfo'><?php echo $this->t('More about this deal'); ?></a></li>
	<?php
	foreach($this->deal->redeemLocs as $l)
	{
		?><li class='arrow'><?php
			echo wm()->get('location.helper')->locationAsText($l->loc,$l->address,$l->zipCode);
			?><br /><?php
			$addr = wm()->get('location.helper')->locationAsText($l->loc,
				$l->address,$l->zipCode,', ');
			$addr = isset($l->lat) && isset($l->lon)
				? $l->lat.', '.$l->lon.' ('.$addr.')'
				: $addr;
			echo CHtml::link($this->t('Map It!'), 'http://maps.google.com/?q='.urlencode($addr),
				array('target' => '_blank'));
		?></li><?php
	}
	?>
</ul>