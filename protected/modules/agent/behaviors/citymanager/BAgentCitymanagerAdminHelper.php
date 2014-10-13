<?php
class BAgentCitymanagerAdminHelper extends UWorkletBehavior
{
	public function afterLayout()
	{
		$result = array_pop(func_get_args());
		if(!$result)
			return $this->owner->taskLayout('citymanager');
	}
	
	public function afterHomeLink()
	{
		if(!app()->user->checkAccess('administrator') && app()->user->checkAccess('citymanager'))
			return url('/agent/citymanager');
	}
}