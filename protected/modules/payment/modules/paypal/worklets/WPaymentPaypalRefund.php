<?php
class WPaymentPaypalRefund extends USystemWorklet
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
			
		$id = explode(':',$order->custom);
		$id = count($id)>1?$id[1]:$id[0];
		
		$p = new PayPal($config);		
		$r = $p->RefundTransaction(array(
			'RTFields' => array(
				'transactionid' => $id,
				'refundtype' => 'Full',
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