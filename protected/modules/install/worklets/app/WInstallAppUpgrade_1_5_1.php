<?php
class WInstallAppUpgrade_1_5_1 extends UInstallWorklet
{
	public $fromVersion = '1.5.0';
	public $toVersion = '1.5.1';
	
	public function getModule()
	{
		return app();
	}
}