<?php
class WDealAdminMenu extends UMenuWorklet
{
	public $space = 'sidebar';
	public $deal;
	public $company;
	
	public function accessRules()
	{
		return array(
			array('allow','roles'=>array('company')),
			array('deny','users'=>array('*'))
		);
	}
	
	public function properties()
	{	
		$items = array();
		
		if(app()->user->checkAccess('administrator'))
		{
			$items[] = array('label'=>$this->t('Manage Deals'), 'url'=>array('/deal/admin/list'));
			$items[] = array('label'=>$this->t('Create New Deal'), 'url'=>array('/deal/admin/create'));
                        $items[] = array('label'=>$this->t('Deal Categories'), 'url'=>array('/deal/category/list'));
		}
		return array('items' => $items);
	}
}