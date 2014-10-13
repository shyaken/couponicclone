<?php
class WPaymentPaypalVoid extends USystemWorklet
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
		$r = $p->DoVoid(array(
			'DVFields' => array(
				'authorizationid' => $order->custom
			),
		));

		if($r['ACK'] == 'Success')
		{
			wm()->get('payment.order')->void($order->id);
			return true;
		}
		return false;
	}
}