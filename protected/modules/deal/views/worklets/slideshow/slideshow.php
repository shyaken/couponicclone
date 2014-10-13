<div class='clearfix'>
	<div class='wlt-DealSlideshow'>
		<ul class='slideshow'><?php
			foreach($this->slides as $key => $slide)
			{
				?><li><?php echo $slide.'<br />';
					echo CHtml::tag('div',array('class' => 'slideDescription'),$this->description[$key]); ?></li><?php
			}
		?></ul>
		<div class='controls'><?php
			foreach($this->controls as $k=>$c)
				echo CHtml::link($k,'#',array('name' => $k));
		?></div>
	</div>
</div>