<?php
class WSubscriptionAdminRemoveEmail extends UDeleteWorklet
{
	public $modelClassName = 'MSubscriptionListEmail';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskDelete($id)
	{
		$m = CActiveRecord::model($this->modelClassName)->findByPk($id);
		wm()->get('subscription.helper')->removeEmailFromList($m->email->email,$m->listId);
	}
}