<?php
class WLocationInstallUpgrade_1_4_1 extends UInstallWorklet
{
	public $fromVersion = '1.4.0';
	public $toVersion = '1.4.1';
	
	public function taskModuleFilters()
	{
		return array(
			'base' => 'location.main',
		);
	}
}