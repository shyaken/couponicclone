<?php
class WInstallAppUpgrade_1_5_4 extends UInstallWorklet
{
	public $fromVersion = '1.5.3';
	public $toVersion = '1.5.4';

	public function getModule()
	{
		return app();
	}
}