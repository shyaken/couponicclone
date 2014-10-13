<?php
class WInstallAppUpgrade_1_5_2 extends UInstallWorklet
{
	public $fromVersion = '1.5.1';
	public $toVersion = '1.5.2';
	
	public function getModule()
	{
		return app();
	}
}