<?php
class WAdminInstallInstall extends UInstallWorklet
{	
	public function taskModuleFilters()
	{
		return array(
			'admin' => 'admin.main',
			'base' => 'admin.main',
			'user' => 'admin.main',
		);
	}
}