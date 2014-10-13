<div class='row'>
	<label><?php echo $color['label']; ?></label>
	<?php
		$this->widget('application.modules.customize.extensions.colorpicker.EColorPicker',array(
			'name' => 'colors['.$id.']',
			'id' => 'colors_'.UHelper::camelize($id),
			'value' => str_replace('#','',$value),
		));
	?>
</div>