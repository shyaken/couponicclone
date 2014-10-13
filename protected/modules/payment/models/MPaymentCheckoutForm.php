<?php
class MPaymentCheckoutForm extends UFormModel
{
	public $type;
	
	public static function module()
	{
		return 'payment';
	}
	
	public function rules()
	{
		return array(
			array('type', 'required')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'type' => $this->t('Please select payment method')
		);
	}
}