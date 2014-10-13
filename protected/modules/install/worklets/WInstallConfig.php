<?php
class WInstallConfig extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	public $permissions;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Installation');
	}
	
	public function properties()
	{
		return array(
			'elements' => array(				
				'attribute' => array('type' => 'hidden'),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Continue'))
			),
			'model' => $this->model
		);
	}
	
	public function taskConfig()
	{
		$this->permissions = array(
			Yii::getPathOfAlias('webroot.assets') => '0777',
			Yii::getPathOfAlias('webroot.storage') => '0777',
			Yii::getPathOfAlias('application.runtime') => '0777',
			Yii::getPathOfAlias('application.config.public.auth') . '.php' => '0777',
			Yii::getPathOfAlias('application.config.public.modules') . '.php' => '0777',
		);
		return parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{
		$this->render('config');
		return parent::taskRenderOutput();
	}
	
	public function taskSave()
	{
		foreach($this->permissions as $item=>$p)
		{
			$valid = strpos($item,'.php')!==false
				? $this->checkFilePermissions($item)
				: $this->checkDirectoryPermissions($item);
			if(!$valid)
				$this->model->addError('attribute',$this->t('Item {item} permissions are set incorrectly.', array(
					'{item}' => $item
				)));
		}	
		
		try
		{
			app()->db->active;
		}
		catch(Exception $e)
		{
			$this->model->addError('attribute', $this->t('Unable to connect to database. Please verify your configuration.'));
		}
	}
	
	public function taskCheckFilePermissions($file)
	{
		$original = file_get_contents($file);
		@file_put_contents($file,'test');
		$new = file_get_contents($file);
		@file_put_contents($file,$original);
		return $new == 'test';
	}
	
	public function taskCheckDirectoryPermissions($dir)
	{
		$file = $dir.DS.'test.txt';
		@file_put_contents($file,'test');
		$r = file_exists($file);
		@unlink($file);
		return $r;
	}
	
	public function successUrl()
	{
		return url('/install/process');
	}
}