<?php
class WInstallAppUpgrade_1_1_0 extends UInstallWorklet
{
	public $fromVersion = '1.0.2';
	public $toVersion = '1.1.0';
	
	public function getModule()
	{
		return app();
	}
	
	public function taskSuccess()
	{
		$config = require(app()->basePath .DS. 'config' .DS. 'public' .DS. 'modules.php');
		$config = $this->upgradeFilters($config);
		UHelper::saveConfig(app()->basePath .DS. 'config' .DS. 'public' .DS. 'modules.php', $config, true);
		parent::taskSuccess();
	}
	
	public function taskUpgradeFilters($config)
	{
		if(isset($config['modules']))
			foreach($config['modules'] as $id=>$cfg)
			{
				if(isset($cfg['filters']))
					foreach($cfg['filters'] as $ind=>$f)
					{
						$config['modules'][$id]['filters'][$ind] = null;
						$config['modules'][$id]['filters'][$f] = true;
					}
				$config['modules'][$id] = $this->upgradeFilters($config['modules'][$id]);
			}
		return $config;
	}
	
	public function taskRenderOutput()
	{
		$this->render('install.views.worklets.upgrade_1_1_0',array('permissions' => array(
			Yii::getPathOfAlias('application.config.public.auth') . '.php' => '0777')));
		return parent::taskRenderOutput();
	}
	
	public function taskSave()
	{
		$item = Yii::getPathOfAlias('application.config.auth') . '.php';
		$p = '0777';
		
		if(app()->file->set($item)->permissions && app()->file->set($item)->permissions != $p)
		{
			$this->model->addError('attribute',$this->t('Item {item} permissions are set incorrectly.', array(
				'{item}' => $item,
			)));
		}
		return parent::taskSave();
	}
}