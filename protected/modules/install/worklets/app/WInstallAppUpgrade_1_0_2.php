<?php
class WInstallAppUpgrade_1_0_2 extends UInstallWorklet
{
	public $fromVersion = '1.0.1';
	public $toVersion = '1.0.2';
	
	public function getModule()
	{
		return app();
	}
}