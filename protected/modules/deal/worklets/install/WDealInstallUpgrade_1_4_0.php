<?php
class WDealInstallUpgrade_1_4_0 extends UInstallWorklet
{
	public $fromVersion = '1.3.4';
	public $toVersion = '1.4.0';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'categories' => '-1',
			'upcoming' => '1',
		));
	}
}