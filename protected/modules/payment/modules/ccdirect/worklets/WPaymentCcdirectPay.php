<?php
Yii::import('application.modules.payment.modules.ccdirect.worklets.WPaymentCcdirectAuthorize',true);
class WPaymentCcdirectPay extends WPaymentCcdirectAuthorize
{
	public $paymentAction = 'sale';
}