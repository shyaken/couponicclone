<?php
class WPaymentWireInstallUpgrade_1_0_1 extends UInstallWorklet
{
	public $fromVersion = '1.0.0';
	public $toVersion = '1.0.1';
	
	public function taskModuleFilters()
	{
return array (
  'payment' => 'payment.wire.main',
);
	}
}