<?php
class MPaymentParamsForm extends UFormModel
{
	public $placedLifetime;
	public $creditsOnly;
	public $cSymbol;
	public $cCode;
	public $convertCode;
	public $convertRate;
	public $convertMethods;
	
	public static function module()
	{
		return 'payment';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe')
		);
	}
}