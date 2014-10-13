<?php
class WDealStats extends UWidgetWorklet
{
	public $totals=array();
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskConfig()
	{
		$sql = "SELECT COUNT(*) AS total FROM {{DealCoupon}} as t, {{PaymentOrder}} as t2
			WHERE t.orderId = t2.id AND t2.`status` = 2";
		$this->totals['coupons'] = app()->db->createCommand($sql)->queryScalar();
		$sql = "SELECT SUM(t2.value-t2.price) FROM {{DealCoupon}} as t,
			{{PaymentOrder}} as t1, {{DealPrice}} as t2 WHERE t.orderId = t1.id AND
			t.priceId = t2.id AND t1.`status` = 2";
		$this->totals['savings'] = app()->db->createCommand($sql)->queryScalar();
		parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{
		$this->render('stats');
	}
}