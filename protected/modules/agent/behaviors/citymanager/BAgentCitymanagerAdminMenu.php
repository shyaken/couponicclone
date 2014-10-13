<?php
class BAgentCitymanagerAdminMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		if(app()->user->checkAccess('citymanager') && !app()->user->checkAccess('administrator'))
		{
			$this->getOwner()->properties['items'][0]['url'] = url('/agent/citymanager');
			$this->getOwner()->insertBefore('Logout',array(
				array('label'=>$this->t('Logout'), 'url'=>array('/agent/citymanager/logout')),
			));
		}
	}
}