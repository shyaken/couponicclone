<?php
class WAgentCitymanagerDelete extends UDeleteWorklet
{	
	public $modelClassName = 'dummy';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskDelete($id)
	{
		MCitymanager::model()->deleteAll('userId=?', array($id));
		MUser::model()->updateByPk($id, array('role' => 'user')); 
	}
}