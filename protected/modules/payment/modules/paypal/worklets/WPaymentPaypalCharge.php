<?php
class WPaymentPaypalCharge extends USystemWorklet
{
	public function run($order)
	{
		include_once(dirname(__FILE__).'/../components/paypal.nvp.class.php');
		
		$config = array(
			'APIUsername' => $this->param('apiUsername'),
			'APIPassword' => $this->param('apiPassword'),
			'APISignature' => $this->param('apiSignature'),
		);
		$config['Sandbox'] = $this->param('sandbox')?true:false;
		
		$p = new PayPal($config);		
		$r = $p->DoCapture(array(
			'DCFields' => array(
				'authorizationid' => $order->custom,
				'amt' => $order->amount,
				'completetype' => 'Complete',
				'currencycode' => $this->module->getParentModule()->param('cCode'),
				'invnum' => $order->id,
			),
		));
		
		if($r['ACK'] == 'Success')
		{
			$order->custom.= ':'.$r['TRANSACTIONID'];
			$order->save();
			wm()->get('payment.order')->charge($order->id);
			return true;
		}
		else
			foreach ($r['ERRORS'] as $error)
				if($error['L_ERRORCODE'] == '10601')
					wm()->get('payment.paypal.void')->run($order);
                
		return false;
	}
}