<?php
class WAdminCron extends UWidgetWorklet
{	
	public $autoLogout=false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Crons Execution Results');
	}
	
	public function beforeAccess()
	{
		if(app()->user->checkAccess('administrator'))
			return;

		if(isset($_GET['s']) && $_GET['s'] == app()->param('cronSecret'))
			if(!$this->login())
			{
				$this->accessDenied();
				return false;
			}
			else
				$this->autoLogout = true;
	}
	
	public function afterAccess()
	{
		if($this->autoLogout)
			app()->user->logout(true);
	}
	
	public function taskLogin()
	{
		$model = MUser::model()->find('role=?',array('administrator'));
		$identity = new UUserIdentity($model->email,$model->password);
		$identity->setModel($model);
		$errorString = $identity->authenticate();
			
		if(is_string($errorString))
			return false;
		
		switch($identity->errorCode)
		{
			case UUserIdentity::ERROR_NONE:
				app()->user->login($identity,0);
				break;
			case UUserIdentity::ERROR_USERNAME_INVALID:
				return false;
				break;
			case UUserIdentity::ERROR_PASSWORD_INVALID:
				return false;
				break;
		}

		if(!app()->user->checkAccess('administrator',array(),false))
			return false;
			
		return true;
	}
	
	public function taskRenderOutput()
	{
		$output = $this->t('Running Crons on {date}.',array('{date}'=>app()->getDateFormatter()->formatDateTime(time(),'full','full')))."\n\n";
		$modules = app()->getModules();
		// temporarily switching theme to the currently selected
		$cfg = require(Yii::getPathOfAlias('application.config.public.modules').'.php');
		$oldTheme = app()->theme;
		app()->theme = isset($cfg['theme'])?$cfg['theme']:$oldTheme;
		
		// opening flag file handle - it won't open until previous cron releases it
		$flag = app()->basePath.'/runtime/flag.txt';
		$handle = fopen($flag,'w');
		
		// running crons from all modules
		foreach ($modules as $m => $d) {
			if ($m == 'admin')
				continue;
			$w = wm()->get($m . '.cron');
			if ($w)
				$output.= $w->run();
		}
		
		// closing flag file
		fclose($handle);
		
		// switching theme to the old one
		app()->theme = $oldTheme;
		echo app()->format->ntext($output);
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Tools') => url('/admin/tools'),
			wm()->get('admin.tools.cron')->title() => url('/admin/tools/cron'),
			$this->title()
		);
	}
}