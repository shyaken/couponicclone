<?php
	$link = $this->link($data);
?><li class='arrow'><?php
	echo CHtml::link($data->name, $link); ?><br />
	<small><?php echo $this->t('Price').': '.m('payment')->format($data->price) ;?></small>
</li>