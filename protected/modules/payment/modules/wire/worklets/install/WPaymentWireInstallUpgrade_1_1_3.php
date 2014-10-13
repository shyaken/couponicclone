<?php
class WPaymentWireInstallUpgrade_1_1_3 extends UInstallWorklet
{
	public $fromVersion = '1.1.2';
	public $toVersion = '1.1.3';
	
	public function taskModuleFilters()
	{
return array (
  'payment' => 'payment.wire.main',
);
	}
}