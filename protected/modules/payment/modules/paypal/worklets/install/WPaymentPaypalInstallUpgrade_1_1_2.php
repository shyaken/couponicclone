<?php
class WPaymentPaypalInstallUpgrade_1_1_2 extends UInstallWorklet
{
	public $fromVersion = '1.1.1';
	public $toVersion = '1.1.2';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
            'method' => 'standard',
		));
	}
}