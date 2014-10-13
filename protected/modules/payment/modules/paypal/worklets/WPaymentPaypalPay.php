<?php
Yii::import('application.modules.payment.modules.paypal.worklets.WPaymentPaypalAuthorize',true);
class WPaymentPaypalPay extends WPaymentPaypalAuthorize
{
	public $paymentAction = 'sale';
}