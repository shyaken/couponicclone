<?php
foreach($this->roles as $id)
{
	$w = wm()->get('agent.'.$id.'.helper');
	?><hr /><h3><?php echo $w->info('title'); ?></h3>
	<p><?php echo $w->info('description'); ?></p>
	<p><?php echo CHtml::link($this->t('Manage {role}', array(
		'{role}' => $w->info('title_s')
	)),url('/agent/'.$id.'/list')); ?></p><?php
}