<?php
class WPaymentSystemAuthorize extends USystemWorklet
{
	public function accessRules()
	{
		return array(
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function run($items,$orderId)
	{		
		wm()->get('payment.order')->authorize($orderId,$orderId);
	}
}