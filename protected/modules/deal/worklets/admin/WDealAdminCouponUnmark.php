<?php
class WDealAdminCouponUnmark extends UWidgetWorklet
{
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeAccess()
	{
		if(!$this->coupon()
			|| (!app()->user->checkAccess('administrator')
				&& !app()->user->checkAccess('company.coupon.access',$this->coupon())))
		{
			$this->accessDenied();
				return false;
		}
	}
	
	public function taskCoupon()
	{
		static $coupon;
		if(!isset($coupon))
			$coupon = isset($_GET['id'])?MDealCoupon::model()->with('order')->findByPk($_GET['id']):null;
		return $coupon;
	}
	
	public function taskConfig()
	{
		$this->coupon()->status = 1;
		$this->coupon()->save();		
		$this->show = false;
	}
}