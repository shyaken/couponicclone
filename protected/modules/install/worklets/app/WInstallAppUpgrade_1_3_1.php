<?php
class WInstallAppUpgrade_1_3_1 extends UInstallWorklet
{
	public $fromVersion = '1.3.0';
	public $toVersion = '1.3.1';
	
	public function getModule()
	{
		return app();
	}
}