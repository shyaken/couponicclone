<?php
class BSubscriptionAdminMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$this->getOwner()->insertBefore('Logout',array(
			array('label'=>$this->t('Subscriptions'), 'url'=>array('/subscription/admin/list'),
				'visible' => app()->user->checkAccess('administrator')),
		));
	}
}