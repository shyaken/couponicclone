<?php
class WBaseLanguageUpdate extends UFormWorklet
{	
	public $modelClassName = 'MBaseLanguageForm';
	public $space = 'inside';
	
	public function title()
	{
		return $this->t('Add New Language');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function afterModel()
	{
		if(isset($_GET['id']))
		{
			$langs = $this->param('languages');
			if(is_array($langs) && isset($langs[$_GET['id']]))
			{
				$this->model->code = $_GET['id'];
				$this->model->name = $langs[$_GET['id']];
			}
		}
	}
	
	public function properties()
	{			
		return array(
			'elements' => array(
				'code' => array('type' => 'text', 'hint' => $this->t('ISO 3166 format. Ex.: en_us')),
				'name' => array('type' => 'text'),
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->t('Add')),
			),
			'model' => $this->model
		);
	}
	
	public function taskSave()
	{
		$langs = $this->param('languages');
		if(!is_array($langs))
			$langs = array();
		$langs[$this->model->code] = $this->model->name;
		
		$file = Yii::getPathOfAlias('application.config.public.modules').'.php';
		$config['modules']['base']['params']['languages'] = null;
		UHelper::saveConfig($file,$config);
		$config['modules']['base']['params']['languages'] = $langs;
		UHelper::saveConfig($file,$config);
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('base.language.list');
		$json = array(
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$listWorklet->getDOMId(). '-grid");
				$("#'.$this->getDOMId().'").closest(".worklet-pushed-content").remove();')
			),
		);
			
		wm()->get('base.init')->addToJson($json);
	}
}