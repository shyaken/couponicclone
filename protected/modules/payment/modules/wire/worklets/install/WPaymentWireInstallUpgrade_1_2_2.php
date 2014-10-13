<?php
class WPaymentWireInstallUpgrade_1_2_2 extends UInstallWorklet
{
	public $fromVersion = '1.2.1';
	public $toVersion = '1.2.2';
	
	public function taskModuleFilters()
	{
return array (
  'payment' => 'payment.wire.main',
);
	}
}