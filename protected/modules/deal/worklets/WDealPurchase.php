<?php
class WDealPurchase extends UWidgetWorklet
{
	public $show = false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'users'=>array('*'))
		);
	}
	
	public function taskConfig()
	{
		$price = MDealPrice::model()->findByPk($_GET['id']);
		$deal = $price->deal;

		if(!wm()->get('deal.helper')->available($deal))
			$this->show = true;
		else
		{
			wm()->get('payment.cart')->put('deal',$price->id,$deal->name,1,$deal->price,
				array('paymentOptions' => $deal->paymentOptions));
			
			app()->request->redirect(url('/payment/cart'));
		}
	}
	
	public function taskRenderOutput()
	{
		$this->render('error', array('error' => $this->t('Deal is no longer available.')));
	}
}