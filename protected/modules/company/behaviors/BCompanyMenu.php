<?php
class BCompanyMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$this->getOwner()->insert('bottom',array(
			array('label'=>$this->t('Company Admin'), 'url'=>array('/company/admin'),
				'visible' => app()->user->checkAccess('company') && !app()->user->checkAccess('citymanager')),
		));
	}
}