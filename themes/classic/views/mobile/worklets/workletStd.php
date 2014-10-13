<div id="<?php echo $id; ?>" class="worklet<?php echo $this->space != 'content' || isset($page) ? ' page' : ''; ?>">
	<?php
		if(isset($toolbar))
			$this->render('toolbar',array('toolbar' => $toolbar));
	?>
	<?php if($title) { ?><h2 class="worklet-title"><?php echo $title; ?></h2><?php } ?>
	<div class="worklet-info notice hide"></div>
	<div class="worklet-content"><?php echo $content; ?></div>
</div>