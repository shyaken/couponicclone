<?php
class FPaymentWireMain extends UWorkletFilter
{
	public function filters()
	{
		return array(
			'payment.admin.view' => array('behaviors' => array('payment.wire.refund')),
		);
	}
}