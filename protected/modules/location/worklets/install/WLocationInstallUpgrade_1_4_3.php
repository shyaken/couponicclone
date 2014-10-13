<?php
class WLocationInstallUpgrade_1_4_3 extends UInstallWorklet
{
	public $fromVersion = '1.4.2';
	public $toVersion = '1.4.3';
	
	public function taskModuleParams()
	{
return CMap::mergeArray(parent::taskModuleParams(),array (
  'country' => 'US',
  'state' => 'NY',
  'city' => 'New York',
));
	}
}