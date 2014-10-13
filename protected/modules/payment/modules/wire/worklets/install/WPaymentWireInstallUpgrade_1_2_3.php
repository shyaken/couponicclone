<?php
class WPaymentWireInstallUpgrade_1_2_3 extends UInstallWorklet
{
	public $fromVersion = '1.2.2';
	public $toVersion = '1.2.3';
	
	public function taskModuleFilters()
	{
return array (
  'payment' => 'payment.wire.main',
);
	}
}