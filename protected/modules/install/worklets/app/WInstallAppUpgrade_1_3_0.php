<?php
class WInstallAppUpgrade_1_3_0 extends UInstallWorklet
{
	public $fromVersion = '1.2.3';
	public $toVersion = '1.3.0';
	
	public function getModule()
	{
		return app();
	}
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
		  'uploadWidget' => '1',
		));
	}
}