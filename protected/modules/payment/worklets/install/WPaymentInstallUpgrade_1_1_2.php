<?php
class WPaymentInstallUpgrade_1_1_2 extends UInstallWorklet
{
	public $fromVersion = '1.1.1';
	public $toVersion = '1.1.2';
	
	public function taskSuccess()
	{
		$orders = MDealOrder::model()->findAll();
		$max = 0;
		foreach($orders as $o)
		{
			// copy order
			$m = new MPaymentOrder;
			$m->id = $o->id;
			$m->userId = $o->userId;
			$m->created = $o->created;
			$m->amount = $o->amount;
			$m->status = $o->status;
			$m->method = $o->method;
			$m->custom = $o->custom;
			$m->save();
			
			// create order item
			$i = new MPaymentOrderItem;
			$i->orderId = $o->id;
			$i->itemModule = 'deal';
			$i->itemId = $o->dealId;
			$i->quantity = $o->quantity;
			$i->save();
			
			// update max
			if($o->id > $max)
				$max = $o->id;
		}
		if($max)
			app()->db->createCommand('ALTER TABLE {{PaymentOrder}} AUTO_INCREMENT = '.$max)->execute();
		
		parent::taskSuccess();
	}
}