<?php
class WPaymentWireInstallUpgrade_1_1_1 extends UInstallWorklet
{
	public $fromVersion = '1.1.0';
	public $toVersion = '1.1.1';
	
	public function taskModuleFilters()
	{
return array (
  'payment' => 'payment.wire.main',
);
	}
}