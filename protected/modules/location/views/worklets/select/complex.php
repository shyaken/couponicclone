<?php
$this->render('search');
?><ul class='country ALL'><?php
	foreach(array_keys($locations) as $country)
	{
		?><li><?php echo CHtml::link(wm()->get('location.helper')->country($country),'#',array('name' => $country)); ?></li><?php
	}
?></ul><?php
foreach($locations as $country=>$locs)
{
?><ul class='country <?php echo $country; ?>'><?php
	foreach($locs as $i=>$loc) {
		if(is_array($loc))
		{
			$char = app()->locale->textFormatter->utf8substr(wm()->get('location.helper')->state($country, $i),0,1);
			?><li class='state char_<?php echo $char; ?>'><?php echo wm()->get('location.helper')->state($country, $i); ?></li><ul class='cities'><?php
			foreach($loc as $l)
				$this->render('city', array('loc' => $l, 'current' => $current));
			?></ul><?php
		}
		else
			$this->render('city', array('loc' => $loc, 'current' => $current));
	}
?></ul><?php	
}