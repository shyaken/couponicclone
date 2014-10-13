<?php
class WInstallAppUpgrade_1_2_0 extends UInstallWorklet
{
	public $fromVersion = '1.1.3';
	public $toVersion = '1.2.0';
	
	public function getModule()
	{
		return app();
	}
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
		  'cronSecret' => md5(UHelper::salt(5)),
		  'timeZone' => '0',
		));
	}
	
	public function report()
	{
		return $this->render('install.views.worklets.upgrade_1_2_0',null,true);
	}
}