<div id="<?php echo $id; ?>" class="worklet"><?php
	$this->render('application.modules.base.views.worklets.hideLink',array('name'=>$this->getDOMId()));
	if($title) {
		?><h3 class="worklet-title"><?php echo $title; ?></h3><?php
	}
?><div class="worklet-info notice hide"></div>
<div class="worklet-content"><?php echo $content; ?></div>
</div>