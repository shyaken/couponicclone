<?php
class WCompanyAdminList extends UListWorklet
{
	public $modelClassName = 'MCompany';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('citymanager')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Manage Companies');
	}
	
	public function beforeConfig()
	{
		if(!app()->user->checkAccess('administrator'))
		{
			$this->addMassButton = false;
			$this->addCheckBoxColumn = false;
		}
	}
	
	public function columns()
	{
		$buttons = app()->user->checkAccess('administrator')
			? '{update} {delete}'
			: '{update}';
			
		return array(
			array('header' => $this->t('Name'),'name' => 'name'),
			array('header' => $this->t('Website'),'name' => 'website', 'type' => 'url'),
			array(
				'header' => $this->t('Location'),
				'name' => 'city',
				'sortable' => false,
				'value' => 'wm()->get("location.helper")->locationAsText($data->loc,false,false," ")',
			),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'template' => $buttons
			),
		);
	}
	
	public function dataProvider()
	{
		$p = parent::dataProvider();
		if(!app()->user->checkAccess('administrator') && app()->user->checkAccess('citymanager'))
		{
			$c = $p->criteria;
			$c = wm()->get('agent.citymanager.helper')->applyCriteria($c, 'company');
			$p->criteria = $c;			
		}
		return $p;
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Companies') => url('/company/admin/list')
		);
	}
	
	public function beforeBuild()
	{
		wm()->add('company.admin.menu');
	}
}