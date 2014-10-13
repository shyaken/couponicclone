<div class="toolbar">
	<div class='txt-center'><?php echo CHtml::image(app()->theme->baseUrl.'/images/mobile/logo.png'); ?></div>
	<?php
		$param = m('deal')->params['categories'];
		if(isset($toolbar['left']) && ($toolbar['left']['href'] != '#wlt-LocationSelect' || $param <= 0))
			echo CHtml::link($toolbar['left']['label'],$toolbar['left']['href'],array(
				'class' => 'button leftButton '.$toolbar['left']['class']
			));
		
		if(isset($toolbar['right']))
			echo CHtml::link($toolbar['right']['label'],$toolbar['right']['href'],array(
				'class' => 'button '.$toolbar['right']['class'],
			));
	?>
</div>