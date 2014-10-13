<?php
class MCcdirectParamsForm extends UFormModel
{
	public $gateway;
	
	public static function module()
	{
		return 'payment.ccdirect';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe')
		);
	}
}