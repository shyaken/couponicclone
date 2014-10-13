<div class='info'><?php
	foreach($info as $block) {
		foreach($block as $k=>$v)
		{
			?><div class='row'>
				<label><?php echo $k.':'; ?></label>
				<?php echo $v; ?>
			</div><?php
		}
		?><hr /><?php
	}
?></div>