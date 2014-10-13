<?php
class FAgentCitymanager extends UWorkletFilter
{
	public function filters()
	{
		return array(
			'admin.menu' => array('behaviors' => array('agent.citymanager.adminMenu')),
			'admin.helper' => array('behaviors' => array('agent.citymanager.adminHelper')),
			'agent.citymanager.index' => array('replace' => 'AgentHomeReplace'),
		);
	}
	
	public function AgentHomeReplace()
	{
		if(app()->user->checkAccess('citymanager'))
		{
			return array('deal.admin.list');
		}
	}
}