<?php
class WLocationInstallUpgrade_1_4_2 extends UInstallWorklet
{
	public $fromVersion = '1.4.1';
	public $toVersion = '1.4.2';
	
	public function taskModuleParams()
	{
return CMap::mergeArray(parent::taskModuleParams(),array (
  'country' => 'US',
  'state' => 'NY',
  'city' => 'New York',
));
	}
}