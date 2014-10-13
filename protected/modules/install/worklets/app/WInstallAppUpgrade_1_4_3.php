<?php
class WInstallAppUpgrade_1_4_3 extends UInstallWorklet
{
	public $fromVersion = '1.4.2';
	public $toVersion = '1.4.3';
	
	public function getModule()
	{
		return app();
	}
}