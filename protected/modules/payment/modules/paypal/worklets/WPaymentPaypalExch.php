<?php
class WPaymentPaypalExch extends UWidgetWorklet
{
	public function taskConfig()
	{
		if(app()->request->isSecureConnection)
			wm()->get('base.init')->requireSecure = true;
		wm()->get('base.init')->setState('subscribe',false);
			
		include_once(dirname(__FILE__).'/../components/paypal.nvp.class.php');
		
		$config = array(
			'APIUsername' => $this->param('apiUsername'),
			'APIPassword' => $this->param('apiPassword'),
			'APISignature' => $this->param('apiSignature'),
		);
		$config['Sandbox'] = $this->param('sandbox')?true:false;
		
		$p = new PayPal($config);		
		$result = $p->GetExpressCheckoutDetails($_GET['token']);
		
		$orderItems = array();
		$amount = 0;
		foreach($result['ORDERITEMS'] as $item)
			$orderItems[] = array(
				'l_name' => urlencode($item['L_NAME']),
				'l_amt' => $item['L_AMT'],
				'l_qty' => $item['L_QTY'],
			);
		
		$r = $p->DoExpressCheckoutPayment(array(
			'DECPFields' => array(
				'token' => $_GET['token'],
				'paymentaction' => $_GET['paymentaction'],
				'payerid' => $result['PAYERID'],
			),
			'PaymentDetails' => array(
				'amt' => $result['AMT'],
				'currencycode' => $result['CURRENCYCODE'],
				'itemamt' => $result['AMT'],
			),
			'OrderItems' => $orderItems
		));
		
		if($r['ACK'] == 'Success')
		{
			if($_GET['paymentaction'] == 'authorization')
				wm()->get('payment.order')->authorize($result['CUSTOM'],$r['TRANSACTIONID']);
			else
				wm()->get('payment.order')->charge($result['CUSTOM'],$r['TRANSACTIONID']);
		}
		
		app()->request->redirect(url('/payment/success', array('id' => $result['CUSTOM'])));
	}
}