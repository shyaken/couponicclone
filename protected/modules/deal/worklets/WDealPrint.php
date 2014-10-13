<?php
class WDealPrint extends UWidgetWorklet
{
	public function accessRules()
	{
		return array(
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function beforeAccess()
	{
		if(!$this->coupon() || !app()->user->checkAccess('user.coupon.access',$this->coupon()))
		{
			$this->accessDenied();
				return false;
		}
	}
	
	public function taskCoupon()
	{
		static $coupon;
		if(!isset($coupon))
		{
			$coupon = isset($_GET['id'])?MDealCoupon::model()->findByPk($_GET['id']):null;
			$coupon->deal->currPrice = $coupon->priceId;
		}
		return $coupon;
	}
	
	public function taskConfig()
	{
		if(!wm()->get('base.helper')->isMobile())
			app()->controller->layout = 'print';
		else
			wm()->get('base.init')->renderType = 'normal';
		
        if(!wm()->get('base.helper')->isMobile())
        	cs()->registerCss('custom.background','body {background: #FFFFFF;}');

		parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{
		$this->render('print');
	}
	
	public function meta()
	{
		$md = parent::meta();
		$md['title'] = $this->coupon()->deal->name;
		return $md;
	}
}