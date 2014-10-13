<?php
class WDealAdminCouponDelete extends UDeleteWorklet
{
	public $modelClassName = 'MDealCoupon';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
}