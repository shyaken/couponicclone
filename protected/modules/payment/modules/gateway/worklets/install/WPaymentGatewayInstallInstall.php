<?php
class WPaymentGatewayInstallInstall extends UInstallWorklet
{
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'name' => 'Custom Payment Gateway',
            'test' => '0',
            'canAuthorize' => '0',
            'canVoid' => '0',
            'canRefund' => '0',
            'canDirect' => '0',
            'cconly' => '',
		));
	}
}