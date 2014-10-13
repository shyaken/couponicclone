<?php
class WDealInstallUpgrade_1_5_2 extends UInstallWorklet
{
	public $fromVersion = '1.5.1';
	public $toVersion = '1.5.2';
	
	public function taskModuleParams()
	{
return CMap::mergeArray(parent::taskModuleParams(),array (
  'twitter' => '',
  'basepath' => '1',
));
	}
}