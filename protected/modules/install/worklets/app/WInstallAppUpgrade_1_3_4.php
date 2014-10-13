<?php
class WInstallAppUpgrade_1_3_4 extends UInstallWorklet
{
	public $fromVersion = '1.3.3';
	public $toVersion = '1.3.4';
	
	public function getModule()
	{
		return app();
	}
}