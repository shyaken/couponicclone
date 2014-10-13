<?php
class WSubscriptionInstallInstall extends UInstallWorklet
{	
	public function taskModuleFilters()
	{
		return array (
		  'admin' => 'subscription.main',
		);
	}
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'emailsLimit' => '100',
		));
	}
}