<?php
class WPaymentWireInstallUpgrade_1_1_0 extends UInstallWorklet
{
	public $fromVersion = '1.0.2';
	public $toVersion = '1.1.0';
	
	public function taskModuleFilters()
	{
return array (
  'payment' => 'payment.wire.main',
);
	}
}