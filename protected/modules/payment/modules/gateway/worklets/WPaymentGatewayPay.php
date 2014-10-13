<?php
Yii::import('application.modules.payment.modules.gateway.worklets.WPaymentGatewayAuthorize',true);
class WPaymentGatewayPay extends WPaymentGatewayAuthorize
{
	public $paymentAction = 'sale';
}