<?php
class MDealOrderList extends MPaymentOrder
{
	public $deal;
	
	public function rules()
	{
		return CMap::mergeArray(parent::rules(),array(
			array('deal','safe','on' => 'search'),
		));
	}
	
	public function relations()
	{
		return CMap::mergeArray(parent::relations(),array(
			'deals' => array(self::MANY_MANY, 'MDealPrice', '{{PaymentOrderItem}}(orderId,itemId)',
				'condition' => "deals_deals.itemModule='deal'",
				'joinType' => 'INNER JOIN',
				'together' => true,
			),
		));
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$with = array('user','deals');
		
		$criteria->with = $with;
		if($this->deal)
			$criteria->compare('deals.dealId',$this->deal,true);
			
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.custom',$this->custom);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.amount',$this->amount);
		$criteria->compare('user.email',$this->userId,true);
		
		if($this->created)
			$criteria->compare('t.created','>='.strtotime($this->created));
		
		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
			'sort' => array(
				'attributes' => array(
					'id', 'status', 'custom', 'amount', 'created',
					'deal' => 'deals.id',
					'userId' => 'user.email',
				),
			)
		));
	}
}