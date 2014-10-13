<?php
class WCompanyAdminMenu extends UMenuWorklet
{
	public $space = 'sidebar';
	
	public function accessRules()
	{
		return array(
			array('allow','roles'=>array('citymanager')),
			array('deny','users'=>array('*'))
		);
	}
	
	public function properties()
	{
		$p = array(
			'items'=>array(
				array('label'=>$this->t('Manage Companies'), 'url'=>array('/company/admin/list')),
				array('label'=>$this->t('Add Company'), 'url'=>array('/company/admin/create')),
			),
			'htmlOptions'=>array(
				//'class' => 'horizontal clearfix'
			)
		);
		
		return $p;
	}
}