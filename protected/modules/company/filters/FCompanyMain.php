<?php
class FCompanyMain extends UWorkletFilter
{
	public function filters()
	{
		return array(
			'admin.menu' => array('behaviors' => array('company.adminMenu')),
			'company.admin.create' => array('replace' => array('company.admin.update')),
			'user.menu' => array(
				'behaviors' => 'CompanyMenu',
				'cacheKey' => 'CompanyCacheKey',
			),
			'user.admin.delete' => array('behaviors' => array('company.userDelete')),
		);
	}
	
	public function CompanyMenu()
	{
		if(app()->user->checkAccess('company'))
			return array('company.menu');
	}
	
	public function CompanyCacheKey()
	{
		return array('company.' . (int)(app()->user->checkAccess('company')));
	}
}