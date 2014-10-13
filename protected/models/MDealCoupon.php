<?php
class MDealCoupon extends UActiveRecord
{	
	public static function module()
	{
		return 'deal';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{DealCoupon}}';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, orderId, dealId, priceId, userId, status, userStatus, hash, redeemLocationId', 'safe', 'on'=>'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'order' => array(self::BELONGS_TO, 'MPaymentOrder', 'orderId'),
			'deal' => array(self::BELONGS_TO, 'MDeal', 'dealId'),
			'price' => array(self::BELONGS_TO, 'MDealPrice', 'priceId'),
			'user' => array(self::BELONGS_TO, 'MUser', 'userId'),
			'redeemLocation' => array(self::BELONGS_TO, 'MDealRedeemLocation', 'redeemLocationId')
		);
	}
	
	public function couponId()
	{
		return $this->dealId.'-'.$this->orderId.'-'.$this->hash;
	}
}