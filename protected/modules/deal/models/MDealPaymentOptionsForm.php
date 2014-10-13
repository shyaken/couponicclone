<?php
class MDealPaymentOptionsForm extends MDeal
{
	public function rules()
	{
		return array(
			array('paymentOptions', 'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'paymentOptions' => $this->t('Enable Following Payment Options'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function afterFind()
	{
		$this->paymentOptions = explode(':',$this->paymentOptions);
		return parent::afterFind();
	}
	
	public function beforeSave()
	{
		$this->paymentOptions = is_array($this->paymentOptions)
			? implode(':',$this->paymentOptions)
			: null;
		return parent::beforeSave();
	}
}