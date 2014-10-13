<?php
class MGatewayParamsForm extends UFormModel
{
	public $name;
	public $test;
	public $canAuthorize;
	public $canVoid;
	public $canRefund;
	public $canDirect;
	public $cconly;
	
	public static function module()
	{
		return 'payment.gateway';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe')
		);
	}
}