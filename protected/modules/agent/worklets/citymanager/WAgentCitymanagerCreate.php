<?php
class WAgentCitymanagerCreate extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	public $user;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'attribute' => array(
					'type' => 'text', 
					'label' => $this->t('Find existing user by email or ID'),
					'required' => true,
					'hint' => $this->t('You can create new user account in admin -> users'),
				),
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->t('Create City Manager')),
			),
			'model' => $this->model
		);
	}
	
	public function taskSave()
	{
		$user = MUser::model()->find('email=:data OR id=:data', array(':data' => $this->model->attribute));
		if(!$user)
			return $this->model->addError('attribute', $this->t('User not found!'));
		if($user->role == 'citymanager')
			return $this->model->addError('attribute', $this->t('This user is already a city manager.'));
		$user->role = 'citymanager';
		$user->save();
		
		$this->user = $user;
	}
	
	public function ajaxSuccess()
	{
		$list = wm()->get('agent.citymanager.list');
		wm()->get('base.init')->addToJson(array(
			'content' => array('replace' => CHtml::script('$.fn.yiiGridView.update("' .$list->getDOMId(). '-grid");')),
			'load' => url('/agent/citymanager/update', array('id' => $this->user->id)),
		));
	}
	
}