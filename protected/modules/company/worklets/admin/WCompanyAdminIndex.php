<?php
class WCompanyAdminIndex extends UWidgetWorklet
{	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskConfig()
	{
		wm()->add('deal.admin.list', null, array('position' => array('after' => $this->id)));
		return parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{
		$this->render('info');
	}
	
	public function taskCompany()
	{
		static $company;
		if(!isset($company))
			$company = MCompany::model()->find('userId=?',array(app()->user->id));
		return $company;
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Company Admin') => url('/company/admin')
		);
	}
}