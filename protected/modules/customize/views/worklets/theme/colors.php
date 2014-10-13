<?php
foreach($default as $group)
{
	?><h4><?php echo $group['label']; ?></h4><?php
	foreach($group['items'] as $id => $color)
		$this->render('color', array('color' => $color, 'id' => $id, 'value' => $scheme && $scheme->color($id) ? $scheme->color($id) : $color['default']));
}