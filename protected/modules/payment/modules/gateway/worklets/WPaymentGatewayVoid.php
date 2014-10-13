<?php
class WPaymentGatewayVoid extends USystemWorklet
{
	public function run($order)
	{
		include_once(dirname(__FILE__).'/../components/Gateway.php');
		$g = new Gateway;
		
		if($this->param('canVoid'))
		{
			$data = array(
				'params' => $this->module->params,
				'orderId' => $order->id,
				'gatewayId' => $order->custom,
				'amount' => $order->amount,
				'currency' => $this->module->getParentModule()->param('cCode'),
			);
			$r = $g->void($data);
			if($r['status'] === true)
			{
				$order->custom.= ':'.$r['gatewayId'];
				$order->save();
				wm()->get('payment.order')->void($order->id);
				return true;
			}
			return false;
		}
		else
		{
			// if the deal fails we'll just deposit order amount to user account
			wm()->get('payment.helper')->addCredit($order->amount,$order->user);
			wm()->get('payment.order')->void($order->id);
			return true;
		}
	}
}