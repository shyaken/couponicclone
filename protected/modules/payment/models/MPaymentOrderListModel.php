<?php
class MPaymentOrderListModel extends MPaymentOrder
{
	public $itemsType;
	
	public function rules()
	{
		return CMap::mergeArray(parent::rules(),array(
			array('itemsType','safe','on' => 'search'),
		));
	}
	
	public function relations()
	{
		return CMap::mergeArray(parent::relations(),array(
			'itemsType' => array(self::HAS_ONE, 'MPaymentOrderItem', 'orderId'),
		));
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$with = array('user');
		
		if($this->itemsType)
		{
			$moduleId = explode('.',$this->itemsType);
			$condition = 'itemModule=:im';
			$params = array(':im' => $moduleId[0]);
			if(isset($moduleId[1]) && $moduleId[1])
			{
				$condition.= ' AND itemId=:iid';
				$params[':iid'] = $moduleId[1];
			}
			$with['itemsType'] = array('condition' => $condition,
				'params' => $params);
		}
		
		$criteria->with = $with;
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
					'userId' => 'user.email',
				),
			)
		));
	}
}