<?php
class WPaymentPaypalInstallInstall extends UInstallWorklet
{
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'name' => 'PayPal',
            'sandbox' => '0',
            'method' => 'standard',
            'business' => '',
            'apiUsername' => '',
            'apiPassword' => '',
            'apiSignature' => '',
            'cconly' => '',
		));
	}
}