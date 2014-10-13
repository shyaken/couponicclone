<?php
class WPaymentInstallInstall extends UInstallWorklet
{
	public function taskModuleFilters()
	{
		return array(
			'admin' => 'payment.main',
			'user' => 'payment.main',
		);
	}
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'placedLifetime' => '7',
			'creditsOnly' => '0',
			'cSymbol' => '$',
        	'cCode' => 'USD',
		));
	}
}