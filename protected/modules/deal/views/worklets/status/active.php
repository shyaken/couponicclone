<div class='txt-center'>
	<h3><?php echo $this->t('{num} bought',array('{num}'=>CHtml::tag('span', array('class'=>'bought'), $bought))); ?></h3>
	<?php
	$rtl = app()->locale->txtDirection == 'r2l';
	$this->widget('zii.widgets.jui.CJuiSlider', array(
		'id' => $this->getDOMId().'_slider',
	    'value'=>$rtl?$required-$bought:$bought,
	    // additional javascript options for the slider plugin
	    'options'=>array(
	        'min'=>0,
	        'max'=>$required,
	        'disabled'=>true,
	        'range'=>$rtl?'max':'min',
	    ),
	    'htmlOptions'=>array(
	        //'style'=>'height:20px;'
	    ),
	));
	?>
	<strong>
		<div class='column'>0</div>
		<div class='txt-right'><?php echo $required; ?></div>
	</strong>
	<?php echo $this->t('{num} more needed to get the deal', array('{num}' => ($required-$bought))); ?>
</div>