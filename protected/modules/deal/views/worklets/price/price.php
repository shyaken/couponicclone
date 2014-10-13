<?php $link = $this->link($data); ?>
<div class='priceOption clearfix'>
	<div class='span-10 colborder'>
		<div class='title'><?php echo CHtml::link($data->name, $link); ?></div>
		<div class='info'><?php echo $this->t('{value} value - {discount} discount - save {save}', array(
			'{value}' => m('payment')->format($data->value),
			'{save}' => m('payment')->format($data->value - $data->dealPrice),
			'{discount}' => $data->discount.'%',
		)); ?></div>
	</div>
	<div class='span-3 last'><?php
	echo CHtml::link(
		m('payment')->format($data->price),
		$link,
		array('class' => 'priceBuyButton')
	);
	?></div>
</div>