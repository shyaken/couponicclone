<?php
class WInstallAppUpgrade_1_2_3 extends UInstallWorklet
{
	public $fromVersion = '1.2.2';
	public $toVersion = '1.2.3';
	
	public function getModule()
	{
		return app();
	}
}