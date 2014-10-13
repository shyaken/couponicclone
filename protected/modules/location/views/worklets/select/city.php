<?php
$params = wm()->get('location.helper')->urlParams($loc);
$char = app()->locale->textFormatter->utf8substr($loc->loc->cityName,0,1);
?><li class='city<?php if($loc->location==$current) echo ' current'; ?> char_<?php echo $char; ?>'><?php echo CHtml::link($loc->loc->cityName, url('/',$params)); ?></li>