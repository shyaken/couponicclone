<?php
class BAgentAdminMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$this->getOwner()->insertBefore('Logout',array(
			array('label'=>$this->t('Agents'), 'url'=>array('/agent'),
				'visible' => app()->user->checkAccess('administrator')),
		));
	}
}