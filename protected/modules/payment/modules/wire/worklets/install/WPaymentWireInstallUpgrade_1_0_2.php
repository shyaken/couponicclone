<?php
class WPaymentWireInstallUpgrade_1_0_2 extends UInstallWorklet
{
	public $fromVersion = '1.0.1';
	public $toVersion = '1.0.2';
	
	public function taskModuleFilters()
	{
return array (
  'payment' => 'payment.wire.main',
);
	}
}