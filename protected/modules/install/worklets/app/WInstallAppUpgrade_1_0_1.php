<?php
class WInstallAppUpgrade_1_0_1 extends UInstallWorklet
{
	public $fromVersion = '1.0.0';
	public $toVersion = '1.0.1';
	
	public function getModule()
	{
		return app();
	}
}