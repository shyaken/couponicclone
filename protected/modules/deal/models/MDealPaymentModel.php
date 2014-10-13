<?php
class MDealPaymentModel extends MDeal
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getFunds()
	{
		static $f = array();
		if(!isset($f[$this->id]))
		{
			$prices = array();
			foreach($this->prices as $p)
				$prices[] = $p->id;
			
			$c = new CDbCriteria;
			$c->with = array('items');
			$c->condition = 'items.itemModule = :module AND t.status = :status';
			$c->params = array(':module' => 'deal', ':status' => 2);
			$c->addInCondition('items.itemId', $prices);
			
			$orders = MPaymentOrder::model()->findAll($c);
			
			if(!count($orders))
				$f[$this->id] = 0;
			else
			{
				$ids = array();
				foreach($orders as $o)
					$ids[] = $o->id;
				
				$query = "SELECT SUM(price.price) FROM {{DealCoupon}} t
					LEFT JOIN {{DealPrice}} price ON t.priceId = price.id
					WHERE t.orderId IN (".implode(',',$ids).")";
				
				if(isset($this->module->params['payoutMode']) && $this->module->params['payoutMode'] == 'redeem')
					$query.= " AND t.`status`=2";
				
				$f[$this->id] = app()->db->createCommand($query)->queryScalar();
			}
		}
		return $f[$this->id];
	}
	
	public function getCommissionHeld()
	{
		$commission = $this->getModule()->param('commission');
		if($this->company->commission)
			$commission = $this->company->commission;
		if($this->commission)
			$commission = $this->commission;
		return round($this->funds/100*$commission,2);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$criteria->with = array('stats');
		
		$criteria->compare('t.active',1);
		
		if($this->status)
			$criteria->compare('t.status',$this->status);
		else
			$criteria->compare('t.status','<>2');
			
		$criteria->compare('t.id',$this->id);
		
		$criteria->mergeWith(new CDbCriteria(array(		
			'condition' => '(t.purchaseMax is not null
				AND t.purchaseMax > 0 AND stats.bought >= t.purchaseMax) OR t.end <= :time',
			'params' => array(':time' => time()),
		)));
		
		return new CActiveDataProvider(__CLASS__, array('criteria'=>$criteria));
	}
}