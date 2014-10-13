<?php
class BCustomizeAdminMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$this->getOwner()->insertBefore('Logout',array(
			array('label'=>$this->t('Customize'), 'url'=>array('/customize'),
				'visible' => app()->user->checkAccess('administrator')),
		));
	}
}