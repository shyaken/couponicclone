<?php
class WPaymentSystemRefund extends USystemWorklet
{
	public function run($order)
	{
		wm()->get('payment.order')->void($order->id);
		return true;
	}
}