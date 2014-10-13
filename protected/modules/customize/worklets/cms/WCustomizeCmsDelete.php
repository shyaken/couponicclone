<?php
class WCustomizeCmsDelete extends UDeleteWorklet
{
	public $modelClassName = 'MCmsPage';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
}