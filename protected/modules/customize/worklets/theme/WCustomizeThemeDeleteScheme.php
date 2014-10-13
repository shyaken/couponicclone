<?php
class WCustomizeThemeDeleteScheme extends UDeleteWorklet
{
	public $modelClassName = 'MThemeColorScheme';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
}