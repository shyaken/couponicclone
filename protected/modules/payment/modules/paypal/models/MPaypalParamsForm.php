<?php
class MPaypalParamsForm extends UFormModel
{
	public $name;
	public $sandbox;
	public $business;
	public $apiUsername;
	public $apiPassword;
	public $apiSignature;
	public $method;
	public $cconly;
	
	public static function module()
	{
		return 'payment.paypal';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe')
		);
	}
}