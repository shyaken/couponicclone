<?php
class WUserInstallUpgrade_1_2_0 extends UInstallWorklet
{
	public $fromVersion = '1.1.3';
	public $toVersion = '1.2.0';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
		  'bannedIPs' => '',
		));
	}
}