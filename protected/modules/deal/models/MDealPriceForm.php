<?php
class MDealPriceForm extends MDealPrice
{
	public $name;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return array(
			array('name,price,value', 'required'),
			array('value', 'numerical', 'min' => 0.01),
			array('couponPrice,main', 'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'name' => $this->t('Name'),
			'price' => $this->t('Coupon Price'),
			'value' => $this->t('Deal Value'),
			'couponPrice' => $this->t('Deal Price'),
		);
	}
	
	public function beforeValidate()
	{
		if(is_array($this->name))
			foreach($this->name as $k=>$v)
				if(!$v)
					unset($this->name[$k]);
		return parent::beforeValidate();
	}
}