<?php
class WInstallAppUpgrade_1_4_2 extends UInstallWorklet
{
	public $fromVersion = '1.4.1';
	public $toVersion = '1.4.2';
	
	public function getModule()
	{
		return app();
	}
}