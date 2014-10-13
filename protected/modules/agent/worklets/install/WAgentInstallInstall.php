<?php
class WAgentInstallInstall extends UInstallWorklet
{
	public function taskModuleFilters()
	{
		return array(
			'agent' => 'agent.citymanager',
			'admin' => array('agent.main', 'agent.citymanager'),
		);
	}
	
	public function taskModuleAuth()
	{
		return array(
			'items' => array(
				'citymanager' => array(2,'City Manager',NULL,NULL),
			),
			'children' => array(
				'administrator' => array('citymanager'),
				'citymanager' => array('user','company.editor','company.edit','company'),
			),
		);
	}
}