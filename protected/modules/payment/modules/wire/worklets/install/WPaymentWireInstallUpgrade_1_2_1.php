<?php
class WPaymentWireInstallUpgrade_1_2_1 extends UInstallWorklet
{
	public $fromVersion = '1.2.0';
	public $toVersion = '1.2.1';
	
	public function taskModuleFilters()
	{
return array (
  'payment' => 'payment.wire.main',
);
	}
}