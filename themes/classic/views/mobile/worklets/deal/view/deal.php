<h1 class='clearfix'>
<?php
	if(isset(wm()->worklets['deal.side']))
	{
		?><a class='slide' href='#wlt-DealSide'><?php echo $this->t('Side Deals'); ?></a><?php
	}
	
	$title = '';
	if(wm()->get('deal.helper')->todays($deal))
		$title.= CHtml::link($this->t('Today\'s Deal:'), url('/deal/view',array('url'=>$deal->url))).' ';
	$title.= $deal->name;
	echo $title; ?>
</h1>
<div class='box'><?php
	app()->controller->worklet('deal.timeLeft', array(
		'start' => $deal->start,
		'end' => $deal->end,
		'timeZone' => $deal->timeZone,
	));
?></div><div class='box'><?php
	if($deal->imageBin)
		app()->controller->worklet('deal.slideshow',array('deal' => $deal, 'scaleTo' => 260));
	app()->controller->worklet('deal.status', array('deal' => $deal));
	?><div class='buttons'><?php echo CHtml::button($this->t('Buy Now!'),array('class' => 'buyButton')); ?>
	<a id="priceDialog" href="#wlt-DealPriceDialog"></a></div>
</div>
<h3><?php echo $this->t('The Fine Print'); ?></h3>
<div class='box'><?php echo app()->format->ntext($deal->finePrint); ?></div>
<h3><?php echo $this->t('Highlights'); ?></h3>
<ul>
<?php
	$highlights = explode("\n",$deal->highlights);
	foreach($highlights as $h)
	{
		?><li><?php echo $h; ?></li><?php
	}
?>
</ul>