<?php
class MDealPrice extends UActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function module()
    {
        return 'deal';
    }
	
	public function tableName()
	{
		return '{{DealPrice}}';
	}
	
    public function rules()
    {
        return array(
			array('dealId, value, price, couponPrice, main, dealId', 'safe'),
			array('dealId, value, price, couponPrice', 'safe', 'on' => 'search'),
        );
    }
	
	public function relations()
	{
		return array(
			'i18n' => array(self::HAS_MANY, 'MI18N', 'relatedId', 'on' => "model='DealPrice'"),
			'dealModel' => array(self::BELONGS_TO, 'MDeal', 'dealId'),
		);
	}
	
    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('dealId',$this->dealId);
		$criteria->compare('value',$this->value);
		$criteria->compare('price',$this->price);
		$criteria->compare('couponPrice',$this->couponPrice);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => 'main DESC, price ASC'
			),
		));
    }
	
	public function getName()
	{
		return $this->translate('name');
	}
	
	public function getDeal()
	{
		$deal = $this->dealModel;
		if(!$deal)
			return null;
		$deal->currPrice = $this->id;
		return $deal;
	}
	
	public function getDealPrice()
	{
		if($this->couponPrice)
			return $this->couponPrice;
		return $this->price;
	}
	
	public function getDiscount()
	{
		return $this->value
			? round((($this->value - $this->dealPrice)/$this->value)*100)
			: 0;
	}
}