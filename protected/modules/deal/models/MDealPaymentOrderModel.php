<?php
class MDealPaymentOrderModel extends MDealPrice
{
	public $hasUsed;
	public $expired;
	public $userId;
	public $status;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function relations()
	{
		return array_merge(parent::relations(),array(
			'orders' => array(self::MANY_MANY, 'MPaymentOrder', '{{PaymentOrderItem}}(itemId,orderId)',
				'joinType' => 'INNER JOIN',
				'on' => "orders_orders.`itemModule` = 'deal'",
				'together' => true,
			),
			'coupons' => array(self::HAS_MANY, 'MDealCoupon', 'priceId',
				'joinType' => 'INNER JOIN',
				'together' => true,
			),
		));
	}
	
	public function getOrderDate($coupons)
	{
		$search = array();
		foreach($coupons as $c)
			$search[] = $c->orderId;
		$c = new CDbCriteria;
		$c->addInCondition('id', $search);
		$c->order = 'created ASC';
		$c->limit = 1;
		$first = MPaymentOrder::model()->find($c);
		return $first->created;
	}
	
	public function getAllCoupons($userId,$hasUsed)
	{
		$condition = 'priceId=? AND userId=?';
		$params = array($this->id,$userId);
		if($hasUsed === true)
			$condition.= ' AND (status=2 OR userStatus=2)';
		elseif($hasUsed === false)
			$condition.= ' AND status=1 AND userStatus=1';
		return MDealCoupon::model()->findAll($condition,$params);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$with = array(
			'dealModel.imageBin',
			'dealModel.stats',
			'orders',
			'coupons' => array(
				'condition' => "coupons.userId = :userId",
				'params' => array(':userId'=>$this->userId)),
		);
		
		if($this->hasUsed === true)
			$with['coupons']['condition'].= ' AND (coupons.status=2 OR coupons.userStatus=2)';
		elseif($this->hasUsed === false)
			$with['coupons']['condition'].= ' AND coupons.status=1 AND coupons.userStatus=1';
		
		$criteria->with = $with;

		$criteria->compare('orders.status',$this->status);
		
		if($this->expired === true)		
			$criteria->mergeWith(new CDbCriteria(array(
				'condition' => 'dealModel.expire is not null AND dealModel.expire <= '.time())));
		elseif($this->expired === false)
			$criteria->mergeWith(new CDbCriteria(array(
				'condition' => 'dealModel.expire is null OR dealModel.expire > '.time())));
				
		$criteria->group = 't.id';

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}