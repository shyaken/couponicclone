<?php
class WPaymentWireAuthorize extends USystemWorklet
{	
	public function accessRules()
	{
		return array(
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function run($items,$orderId=null,$options=array())
	{
		$order = wm()->get('payment.order')->order($orderId);
		
		$html = strtr($this->module->param('info'),array(
			'{orderID}' => $orderId,
			'{amount}' => $order->amount,
		));
		
		$subject = $this->t('{site}: Order #{orderID} - Awaiting Payment', array(
			'{orderID}' => $orderId,
			'{site}' => app()->name,
		));
		
		app()->mailer->send(app()->user->model(), null, array(
			'subject' => $subject,
			'htmlBody' => $html,
			'plainBody' => strip_tags($html),
		));
		
		wm()->get('base.init')->addToJson(array(
			'redirect' => '',
			'content' => array(
				'replace' => $html,
				'focus' => true,
			),
		));
	}
}