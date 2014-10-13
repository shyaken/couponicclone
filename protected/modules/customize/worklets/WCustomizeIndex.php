<?php
class WCustomizeIndex extends UWidgetWorklet
{
	public $tools;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Customize');
	}
	
	public function taskConfig()
	{
		$this->tools = array('cms', 'theme');
		parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{		
		$this->render('list');
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Customize') => url('/customize')
		);
	}
}