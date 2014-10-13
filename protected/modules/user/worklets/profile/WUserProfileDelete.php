<?php
class WUserProfileDelete extends UDeleteWorklet
{
	public $modelClassName = array(
		'MUserProfile' => 'settingId',
		'MUserProfileSetting' => 'id'
	);
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
}