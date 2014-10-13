<?php
class WDealReviewDelete extends UDeleteWorklet
{	
	public $modelClassName = 'MDealReview';
	
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
			$deals[$id] = MDealReview::model()->findByPk($id)->deal;
		
		return $deals[$id];
	}
	
	public function taskDeleteByDeal($id)
	{
		if(app()->user->checkAccess('administrator'))
			MDealReview::model()->deleteAll('dealId = ?',array($id));
	}
}