<?php
class WDealLocationAll extends UWidgetWorklet
{
	public $show = false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeAccess()
	{
		if(isset($_GET['dealId']) && wm()->get('deal.edit.helper')->authorize($_GET['dealId']))
			return true;
		$this->accessDenied();
		return false;
	}
	
	public function taskConfig()
	{
		MDealLocation::model()->deleteAll('dealId=?',array($_GET['dealId']));
		$m = new MDealLocation;
		$m->dealId = $_GET['dealId'];
		$m->location = 0;
		$m->save();
	}
}