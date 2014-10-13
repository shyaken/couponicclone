<?php
class WPaymentCron extends UCronWorklet
{
	public function taskBuild()
	{
		$this->removePlacedOrders();
	}
	
	public function taskRemovePlacedOrders()
	{
		$c = 0;
		
		if(!$this->param('placedLifetime'))
			return;
		
		$endTime = time() - $this->param('placedLifetime')*3600*24;
		$orders = MPaymentOrder::model()->findAll('status=0 AND created < '.$endTime);
		$w = wm()->get('payment.admin.delete');
		
		foreach($orders as $order)
		{
			$w->delete($order->id);
			$c++;
		}
		
		$this->addResult($this->t('{num} "placed" orders have been deleted.', array('{num}'=>$c)));
	}
}