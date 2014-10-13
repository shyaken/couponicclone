<?php
class WInstallAppUpgrade_1_5_3 extends UInstallWorklet
{
	public $fromVersion = '1.5.2';
	public $toVersion = '1.5.3';
	
	public function getModule()
	{
		return app();
	}
}