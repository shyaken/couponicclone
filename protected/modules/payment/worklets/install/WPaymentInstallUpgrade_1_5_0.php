<?php
class WPaymentInstallUpgrade_1_5_0 extends UInstallWorklet
{
	public $fromVersion = '1.4.4';
	public $toVersion = '1.5.0';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
		  'placedLifetime' => '7',
		  'creditsOnly' => '0',
		));
	}
}