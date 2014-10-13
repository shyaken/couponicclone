<?php
class WAdminSetupMenu extends UMenuWorklet
{
	public $space = 'sidebar';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{
		return array('items'=>array(
			array('label'=>$this->t('Settings'), 'url'=>array('/admin/setup')),
			array('label'=>$this->t('Manage Modules'), 'url'=>array('/admin/modules')),
			array('label' => $this->t('Manage Locations'), 'url' => array('/location/admin/list')),
		));
	}
}