<?php
class MDealCouponListModel extends MDealCoupon
{
	public $companyId;
	
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$criteria->with = array('order','deal','user','price');

		if($this->id)
			$criteria->compare("concat(t.dealId,'-',t.orderId,'-',t.hash)",$this->id,true);
		
		if($this->status == 1)
		{
			$criteria->compare('t.status',$this->status);
			$criteria->mergeWith(new CDbCriteria(array(
				'condition' => 'deal.expire is null OR deal.expire > '.time())));
		}
		elseif($this->status == 2)
		{
			$criteria->compare('t.status',$this->status);
		}
		elseif($this->status == 3)
		{
			$criteria->compare('t.status',1);
			$criteria->mergeWith(new CDbCriteria(array(
				'condition' => 'deal.expire is not null AND deal.expire <= '.time())));
		}
		
		$criteria->compare('t.orderId',$this->orderId);
		
		$criteria->compare('t.priceId',$this->priceId);
		
		$criteria->compare('deal.companyId',$this->companyId);
		
		$criteria->compare('user.email',$this->userId,true);
		
		$criteria->compare('t.dealId',$this->dealId,true);

		$criteria->compare('t.redeemLocationId',$this->redeemLocationId,true);
		
		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
			'sort' => array(
				'attributes' => array(
					'id', 'orderId', 'status',
					'dealId',
					'userId' => 'user.email',
				),
			),
		));
	}
}