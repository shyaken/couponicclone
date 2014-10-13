<?php
class WDealAdminPay extends UWidgetWorklet
{
	public $html;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeAccess()
	{
		if(!$this->deal() || $this->deal()->status!=1)
		{
			$this->accessDenied();
			return false;
		}
	}
	
	public function taskDeal()
	{
		static $deal;
		if(!isset($deal))
			$deal = isset($_GET['id'])?MDealPaymentModel::model()->findByPk($_GET['id']):null;
		return $deal;
	}
	
	public function taskConfig()
	{		
		$funds = $this->deal()->funds;
		$amount = $funds - $this->deal()->commissionHeld;
		
		$processor = 'payment.paypal.pay';
		$w = wm()->get($processor);
		$w->module->params['method'] = 'standard';
		$this->html = $w->run(
			array(
				array(
					'name' => $this->t('Deal Payout: {deal}',array('{deal}' => $this->deal()->name)),
					'price' => $amount,
					'quantity' => 1
				),
			),
			null,		
			array(
				'return' => aUrl('/deal/admin/list'),
				'cancel_return' => aUrl('/deal/admin/list'),
				'notify_url' => null,
				'business' => $this->deal()->company->payment,
			)
		);
	}
	
	public function taskRenderOutput()
	{
		echo $this->html;
	}
}