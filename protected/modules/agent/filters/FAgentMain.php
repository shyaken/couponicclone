<?php
class FAgentMain extends UWorkletFilter
{
	public function filters()
	{
		return array(
			'admin.menu' => array('behaviors' => array('agent.adminMenu')),
		);
	}
}