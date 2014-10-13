<?php
class WCustomizeCmsDeleteBlock extends UDeleteWorklet
{
	public $modelClassName = 'MCmsBlock';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
}