<?php
class WInstallAppUpgrade_1_1_1 extends UInstallWorklet
{
	public $fromVersion = '1.1.0';
	public $toVersion = '1.1.1';
	
	public function getModule()
	{
		return app();
	}
}