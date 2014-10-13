<?php
class WInstallConfirm extends UConfirmWorklet
{	
	public $modules = array();
	
	public function taskAddModule($module)
	{
		$this->modules[] = $module;
	}
	
	public function taskConfig()
	{
		wm()->get('install.helper')->loadModules();
		self::$yesButtonLabel = $this->t('Confirm');
		self::$noButtonLabel = $this->t('Cancel');		
		
		if(!count($_POST))
		{
			list($type,$moduleId) = $this->typeModule();
			$i = wm()->get('install.helper');
			$i->prepare($type,$moduleId);
			if(!count($this->modules))
			{
				$this->yes();
				$this->success();
			}
		}
		
		parent::taskConfig();
	}
	
	public function taskDescription()
	{
		$modules = array();
		foreach($this->modules as $m)
			$modules[] = $m->title;
		
		
		return $this->t('Upgrader is going to install (! not upgrade !) following modules: {modules}.
		If you are upgrading to a new version of the script
		and this new version includes some new module(s) it\'s ok to allow upgrader to install it/them.
		But if you notice, that upgrader is going to re-install some module which you\'ve already
		got installed from previos versions it might be a bug and allowing it to do this
		is likely to cause errors on your site, including partial or complete data loss.
		If you experience such problem please do not click on the "Confirm" button below and
		open a support ticket from your account.', array(
			'{modules}' => implode(', ',$modules)
		));
	}
	
	public function taskTypeModule()
	{
		return explode('.',$_GET['install'],2);
	}
	
	public function taskYes()
	{
		list($type,$moduleId) = $this->typeModule();
		$i = wm()->get('install.helper');
		$i->clearReports();
		$i->clearInstallers();
		$i->installer($type,$moduleId);

		$this->successUrl = url('/install/config',
			array('perms' => ($moduleId!='app' || $type == 'upgrade')));
	}
}