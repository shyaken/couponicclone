<?php
class MWireParamsForm extends UFormModel
{
	public $name;
	public $info;
	
	public static function module()
	{
		return 'payment.wire';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe')
		);
	}
}