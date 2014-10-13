<?php
class WCustomizeInstallInstall extends UInstallWorklet
{
	public function taskModuleFilters()
	{
		return array(
			'admin' => 'customize.main',
			'base' => 'customize.main',
		);
	}
}