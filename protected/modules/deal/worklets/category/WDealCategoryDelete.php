<?php
class WDealCategoryDelete extends UDeleteWorklet
{
	public $modelClassName = 'MDealCategory';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskDelete($id)
	{
		MI18N::model()->deleteAll('model=? AND relatedId=?', array('DealCategory', $id));	
		parent::taskDelete($id);
	}
}