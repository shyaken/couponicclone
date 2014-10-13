<?php
class WPaymentSystemCharge extends USystemWorklet
{
	public function run($order)
	{
		wm()->get('payment.order')->charge($order->id);
		return true;
	}
}