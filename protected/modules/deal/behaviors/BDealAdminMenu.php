<?php
class BDealAdminMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$this->getOwner()->insertBefore('Logout',array(
			array('label'=>$this->t('Deals'), 'url'=>array('/deal/admin/list'),
				'visible' => app()->user->checkAccess('citymanager')),
		));
	}
}