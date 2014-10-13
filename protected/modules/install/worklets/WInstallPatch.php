<?php
class WInstallPatch extends USystemWorklet
{
	public function taskPatch()
	{
		if(!param('version') && param('installed'))
		{
			$config = $this->patchVersions();
			UHelper::saveConfig(app()->basePath.DS.'config'.DS.'public'.DS.'modules.php',$config);
		}
	}
	
	public function taskPatchVersions($module=null)
	{
		$config = array();		
		$module = $module?$module:app();
		
		if(!$module->param('version'))
		{
			$config['params']['version'] = '1.0.0';
			$module->params['version'] = '1.0.0';
		}
			
		foreach($module->getModules() as $id=>$cfg)
			$config['modules'][$id] = $this->patchVersions($module->getModule($id));
			
		return $config;
	}
}