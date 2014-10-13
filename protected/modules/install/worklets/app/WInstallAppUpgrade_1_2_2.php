<?php
class WInstallAppUpgrade_1_2_2 extends UInstallWorklet
{
	public $fromVersion = '1.2.1';
	public $toVersion = '1.2.2';

	public function getModule()
	{
		return app();
	}
}