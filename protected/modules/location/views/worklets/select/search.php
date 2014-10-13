<div class='search clearfix append-bottom'><?php
	if(!wm()->get('location.helper')->defaultCountry()) {
		?><div class='column currentCountry'></div><?php
	}
	?><div class='column alphabet'></div><?php
	if(!wm()->get('location.helper')->defaultCountry()) {
		?><div class='floatRight'><?php echo CHtml::link($this->t('Countries'), '#', array('class' => 'showCountries')); ?></div><?php
	}
?></div>