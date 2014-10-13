<?php
class WAgentIndex extends UWidgetWorklet
{
	public $roles;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Manage Agents');
	}
	
	public function taskConfig()
	{
		$this->roles = array(
			'citymanager',
		);
		parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{		
		$this->render('list');
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Agents') => url('/agent')
		);
	}
}