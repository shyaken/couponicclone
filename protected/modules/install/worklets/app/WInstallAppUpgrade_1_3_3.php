<?php
class WInstallAppUpgrade_1_3_3 extends UInstallWorklet
{
	public $fromVersion = '1.3.2';
	public $toVersion = '1.3.3';

	public function getModule()
	{
		return app();
	}
}