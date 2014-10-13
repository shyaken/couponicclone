<?php
class WPaymentGatewayCharge extends USystemWorklet
{
	public function run($order)
	{
		include_once(dirname(__FILE__).'/../components/Gateway.php');
		$g = new Gateway;
		
		if($this->param('canAuthorize'))
		{
			$data = array(
				'params' => $this->module->params,
				'orderId' => $order->id,
				'gatewayId' => $order->custom,
				'amount' => $order->amount,
				'currency' => $this->module->getParentModule()->param('cCode'),
			);
			$r = $g->capture($data);
			if($r['status'] === true)
			{
				$order->custom.= ':'.$r['gatewayId'];
				$order->save();
				wm()->get('payment.order')->charge($order->id);
				return true;
			}
			return false;
		}
		else
		{
			wm()->get('payment.order')->charge($order->id);
			return true;
		}
	}
}