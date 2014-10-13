<?php
class WDealLocationRedeemDelete extends UDeleteWorklet
{	
	public $modelClassName = 'MDealRedeemLocation';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeDelete($id)
	{
		if(!wm()->get('deal.edit.helper')->authorize($this->deal($id)))
		{
			$this->accessDenied();
			return false;
		}
	}
	
	public function taskDeal($id)
	{
		static $deals=array();
		
		if(!isset($deals[$id]))
			$deals[$id] = MDealRedeemLocation::model()->findByPk($id)->deal;
		
		return $deals[$id];
	}
}