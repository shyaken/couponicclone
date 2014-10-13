<?php
class WUserAdminBan extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function afterConfig()
	{
		$this->model->attribute = str_replace(";","\n",$this->param('bannedIPs'));
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'attribute' => array('type' => 'textarea',
					'hint' => $this->t('One ip per line. You can use masks. Ex.: 127.0.0.*'),
					'label' => $this->t('List of Banned IPs')),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Ban')),
			),
			'model' => $this->model,
		);
	}
	
	public function taskSave()
	{
		$config['modules']['user']['params']['bannedIPs'] = str_replace("\n",";",$this->model->attribute);
		UHelper::saveConfig(Yii::getPathOfAlias('application.config.public.modules').'.php',$config);
	}
	
	public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array('info' => array(
			'replace' => $this->t('Banned IPs list has been successfully updated.'),
			'fade' => 'target',
			'focus' => true,
		)));
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Users') => url('/user/admin/list'),
			$this->t('Ban Users by IP'),
		);
	}
	
	public function beforeBuild()
	{
		wm()->add('user.admin.menu');
	}
}