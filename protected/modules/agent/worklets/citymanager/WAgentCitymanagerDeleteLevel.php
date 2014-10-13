<?php
class WAgentCitymanagerDeleteLevel extends UDeleteWorklet
{	
	public $modelClassName = 'MCitymanager';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
}