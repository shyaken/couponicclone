<?php
class WPaymentWireRefund extends USystemWorklet
{
	public function run($order)
	{
		// if the deal fails we'll just deposit order amount to user account
		wm()->get('payment.helper')->addCredit($order->amount,$order->user);
		wm()->get('payment.order')->void($order->id);
		return true;
	}
}