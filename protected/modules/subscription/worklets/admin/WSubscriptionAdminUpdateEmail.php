<?php
class WSubscriptionAdminUpdateEmail extends UFormWorklet
{
	public $modelClassName = 'MSubscriptionEmail';
	public $primaryKey = 'id';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Add/Edit Subscriber');
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'email' => array('type' => 'text'),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save')),
			),
			'model' => $this->model
		);
	}
	
	public function taskSave()
	{
		if($this->isNewRecord)
			wm()->get('subscription.helper')->addEmailToList($this->model->email,$_GET['listId']);
		else
			return parent::taskSave();
	}
	
	public function ajaxSuccess()
	{
		$list = wm()->get('subscription.admin.emails');
		wm()->get('base.init')->addToJson(array(
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$list->getDOMId(). '-grid");
					$.uniprogy.dialogClose();'),
			)
		));
	}
}