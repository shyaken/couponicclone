<?php
class WPaymentCcdirectAuthorize extends USystemWorklet
{
	public $paymentAction = 'authorization';
	
	public function run($items,$orderId=null,$options=array())
	{
		$form = wm()->get('payment.ccdirect.form');
		$form->init();
		if(!$form->form->hasErrors())
		{
			$order = wm()->get('payment.order')->order($orderId);
			$order->method = $this->param('gateway');
			$order->save();
			
			$w = wm()->get('payment.'.$this->param('gateway').'.direct');
			$w->paymentAction = $this->paymentAction;
			$w->formWorklet = $form;
			$w->run($items,$orderId,$options);
		}
	}
}