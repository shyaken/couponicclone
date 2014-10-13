<?php
class WDealInstallUpgrade_1_5_4 extends UInstallWorklet
{
	public $fromVersion = '1.5.3';
	public $toVersion = '1.5.4';
	
	public function taskModuleParams()
	{
return CMap::mergeArray(parent::taskModuleParams(),array (
  'twitter' => '',
  'basepath' => '1',
));
	}
}