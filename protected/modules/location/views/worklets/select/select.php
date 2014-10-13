<ul>
<?php
foreach($locations as $country=>$locs)
{
	if($showCountry)
	{
		?><li class='country'><?php echo wm()->get('location.helper')->country($country); ?><?php
	}
	
	foreach($locs as $loc)
	{
		if(is_array($loc))
			foreach($loc as $l)
			{
				$params = wm()->get('location.helper')->urlParams($l);
				?><li class='city<?php if($l->location==$current) echo ' current'; ?>'><?php echo CHtml::link($l->loc->cityName, url('/',$params)); ?></li><?php
			}
		else
		{
			$params = wm()->get('location.helper')->urlParams($loc);
			?><li class='city<?php if($loc->location==$current) echo ' current'; ?>'><?php echo CHtml::link($loc->loc->cityName, url('/',$params)); ?></li><?php
		}
	}
	
	if($showCountry)
	{
		?></li><?php
	}
}
?>
</ul>