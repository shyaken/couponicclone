<?php
class WCompanyInstallInstall extends UInstallWorklet
{
	public function taskModuleFilters()
	{
		return array(
			'admin' => 'company.main',
			'company' => 'company.main',
			'user' => 'company.main',
		);
	}
	
	public function taskModuleAuth()
	{
		return array(
			'items' => array(
				'company' => array(2,'Company Account (Viewer)',NULL,NULL),
				'company.editor' => array(2,'Company Account (Editor)',NULL,NULL),
				'company.owner' => array(1,'company owner','return $params->userId == app()->user->id;',NULL),
				'company.edit' => array(0,'edit deal or company info',NULL,NULL),
			),
			'children' => array(
				'administrator' => array('company','company.editor','company.edit'),
				'company' => array('user'),
				'company.editor' => array('user','company','company.owner'),
				'company.owner' => array('company.edit'),
			),
		);
	}
}