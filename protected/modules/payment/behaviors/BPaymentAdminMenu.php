<?php
class BPaymentAdminMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$this->getOwner()->insertBefore('Logout',array(
			array('label'=>$this->t('Accounting'), 'url'=>array('/payment/admin/list'),
				'visible' => app()->user->checkAccess('administrator')),
		));
	}
}