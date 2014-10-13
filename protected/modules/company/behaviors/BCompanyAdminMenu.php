<?php
class BCompanyAdminMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$this->getOwner()->insertBefore('Logout',array(
			array('label'=>$this->t('Companies'), 'url'=>array('/company/admin/list'),
				'visible' => app()->user->checkAccess('citymanager')),
		));
	}
}