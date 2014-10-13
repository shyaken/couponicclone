<?php
class WInstallAppUpgrade_1_2_1 extends UInstallWorklet
{
	public $fromVersion = '1.2.0';
	public $toVersion = '1.2.1';
	
	public function getModule()
	{
		return app();
	}
}