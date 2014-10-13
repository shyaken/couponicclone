<?php
class WCustomizeThemeCurrent extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	
	public function title()
	{
		return $this->t('Current Theme');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function afterConfig()
	{
		$config = require(app()->basePath .DS. 'config' .DS. 'public' .DS. 'modules.php');
		$this->model->attribute = isset($config['theme'])?$config['theme']:null;
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'attribute' => array(
					'type' => 'dropdownlist', 'items' => CHtml::listData(wm()->get('customize.theme.helper')->list(),'id','name'),
					'prompt' => $this->t('No theme'),
					'label' => $this->t('Select Theme'),
				),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Switch'))
			),
			'model' => $this->model
		);
	}
	
	public function taskSave()
	{
		$config = array('theme' => $this->model->attribute);
		UHelper::saveConfig(app()->basePath .DS. 'config' .DS. 'public' .DS. 'modules.php',$config);
	}
	
	public function successUrl()
	{
		return url('/customize/theme/list');
	}
}