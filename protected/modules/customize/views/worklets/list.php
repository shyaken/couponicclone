<?php
foreach($this->tools as $id)
{
	$w = wm()->get('customize.'.$id.'.helper');
	?><hr /><h3><?php echo $w->title(); ?></h3>
	<p><?php echo $w->description(); ?></p>
	<p><?php echo CHtml::link($this->t('Go to {name}', array('{name}' => $w->title())),url('/customize/'.$id.'/list')); ?></p><?php
}