<div class='txt-center'>
	<h3><?php echo $this->t('{num} bought',array('{num}'=>CHtml::tag('span', array('class'=>'bought'), $bought))); ?></h3>
	<h4><?php echo $this->t('The deal is closed.'); ?></h4>
	<?php if($this->deal->cacheValue('tippedTime')) { ?>
	<div>
		<?php echo $this->t('Tipped at {time} with {num} bought', array(
			'{time}' => app()->getDateFormatter()->formatDateTime(utime($this->deal->cacheValue('tippedTime'),false), null),
			'{num}' => $this->deal->cacheValue('tippedAmount')			
		));	?>
	</div>
	<?php } ?>
</div>