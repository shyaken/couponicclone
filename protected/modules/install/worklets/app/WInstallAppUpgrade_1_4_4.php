<?php
class WInstallAppUpgrade_1_4_4 extends UInstallWorklet
{
	public $fromVersion = '1.4.3';
	public $toVersion = '1.4.4';
	
	public function getModule()
	{
		return app();
	}
}