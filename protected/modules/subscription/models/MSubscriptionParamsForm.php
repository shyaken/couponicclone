<?php
class MSubscriptionParamsForm extends UFormModel
{
	public $emailsLimit;
	
	public static function module()
	{
		return 'subscription';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe')
		);
	}
}