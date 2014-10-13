<?php
class WPaymentOrder extends USystemWorklet
{
	/**
	 * Verifies the order.
	 * @param integer dummy ID - should be '0' always
	 * @param integer ordered quantity
	 * @return boolean true or error message as a string
	 */
	public function taskVerify($id,$quantity,$user=null)
	{	
		if($id == 0)
			return $this->verifyCredit($quantity, $user);
		elseif($id == 1){
			$w = wm()->get('payment.checkout');
			$w->amount += $quantity;
			$w->items[] = array(
				'name' => $this->t('{site} Credits',array('{site}' => app()->name)),
				'price' => '1',
				'quantity' => $quantity,
			);
			return true;
		}
				
		return $this->t('Unknown product.');
	}
	
	/**
	 * @param integer amount
	 * @return boolean true or error as a string
	 */
	public function taskVerifyCredit($amount, $user = null)
	{
		if(!$amount)
			return true;
			
		if($amount <= wm()->get('payment.helper')->credit($user))
		{
			$w = wm()->get('payment.checkout');
			$w->amount-= $amount;
			$w->items[] = array(
				'name' => $this->t('{site} Credits',array('{site}' => app()->name)),
				'price' => '-1',
				'quantity' => $amount
			);
			return true;
		}
		else
			return CHtml::link($this->t('You do not have enough credits. Please click here to add more credits to your account.'), url('/payment/credits'));
	}
	
	/**
	 * Places an order.
	 * @param array order items
	 * @param integer order amount
	 * @param string method (patment gateway)
	 * @return MPaymentOrder order model
	 */
	public function taskPlace($items,$amount,$method)
	{
		$m = new MPaymentOrder;
		$m->userId = app()->user->id;
		$m->amount = $amount;
		$m->method = $method;
		$m->status = 0;
		$m->save();
		
		foreach($items as $module=>$item)
		{
			foreach($item as $id=>$quantity)
			{
				$i = new MPaymentOrderItem;
				$i->orderId = $m->id;
				$i->itemModule = $module;
				$i->itemId = $id;
				$i->quantity = $quantity;
				$i->save();
			}
		}
		
		return $m;
	}
	
	/**
	 * Authorizes the order.
	 * @param integer order ID
	 * @param integer payment gateway order ID
	 * @param boolean whether authorization should be done in a quiet mode (no emails sent out)
	 * @return boolean false on failure
	 */
	public function taskAuthorize($id,$custom,$quiet=false)
	{
		$order = $this->order($id);
		// authorize only order with '0' status - placed
		if(!$order || $order->status != 0)
			return false;
		
		// we need to re-validate this order before doing final authorization
		$errors = array();
		foreach($order->items as $item)
			if(($error=wm()->get($item->itemModule.'.order')->verify($item->itemId,$item->quantity, $order->user))!==true)
				$errors[] = $error;

		if(count($errors))
		{
			$errorsTxt = implode("\n",$errors);
			if(app()->request->isAjaxRequest)
				return wm()->get('base.init')->addToJson(array('info' => array('appendReplace' => nl2br($errorsTxt))));
			throw new CHttpException(403, $errorsTxt);
		}
			
		// update order status - 1 (authorized)
		$order->status = 1;
		$order->custom = $custom;
		$order->save();
		
		// set user role to 'user' - even if they are unverified or unapprove
		// once they pay - they should get approved and verified automatically
		$u = $order->user;
		if($u->role == 'unverified' || $u->role == 'unapproved')
		{
			$u->role = 'user';
			$u->save();
		}
		
		// authorize items separately
		foreach($order->items as $item)
			wm()->get($item->itemModule.'.order')->authorized($order,$item,$quiet);
	}
	
	/**
	 * Does necesssary actions if the order has been authorized.
	 * @param MPaymentOrder order model
	 * @param MPaymentOrderItem order item model
	 * @param boolean whether to send an authorization confirmation email or not
	 */
	public function taskAuthorized($order,$item)
	{
		if($item->itemId==0)
			wm()->get('payment.helper')->addCredit(-($item->quantity),$order->user,$this->t('Credits used to pay for order #{id}', array(
				'{id}' => $order->id
			)));
		elseif($item->itemId==1)
			wm()->get('payment.helper')->addCredit(+($item->quantity),$order->user,$this->t('Credits purchased. Order #{id}', array(
				'{id}' => $order->id
			)));
	}
	
	/**
	 * Charges the order.
	 * @param integer order ID
	 * @param integer payment gateway order ID
	 */
	public function taskCharge($id,$custom=null)
	{
		$order = $this->order($id);
		// charge only order with status < 2 (placed and authorized)
		if(!$order || $order->status >= 2)
			return false;
		if($order->status == 0 && $custom!==null)
			$this->authorize($id,$custom,true);
			
		// update order status - 2 (charged)
		$order->status = 2;
		$order->save();
		
		// charge items separately
		foreach($order->items as $item)
			wm()->get($item->itemModule.'.order')->charged($order,$item);
	}
	
	/**
	 * Does necesssary actions if the order has been charged.
	 * @param MPaymentOrder order model
	 * @param MPaymentOrderItem order item model
	 */
	public function taskCharged($order,$item)
	{		
		return;
	}
	
	/**
	 * Voids the order.
	 * @param integer order ID
	 */
	public function taskVoid($id)
	{
		$order = $this->order($id);
		if(!$order || $order->status == 3)
			return false;
		$order->status = 3;
		$order->save();

		// void items separately
		foreach($order->items as $item)
			wm()->get($item->itemModule.'.order')->voided($order,$item);
	}
	
	/**
	 * Does necesssary actions if the order has been voided/refunded.
	 * @param MPaymentOrder order model
	 * @param MPaymentOrderItem order item model
	 */
	public function taskVoided($order,$item)
	{		
		if($item->itemId==0)
			wm()->get('payment.helper')->addCredit($item->quantity,$order->user,$this->t('Order #{id} refund: credits returned.', array(
				'{id}' => $order->id
			)));
		elseif ($item->itemId == 1)
			wm()->get('payment.helper')->addCredit(-($item->quantity),$order->user,$this->t('Order #{id} refund: credits deducted.', array(
				'{id}' => $order->id
			)));
	}
	
	/**
	 * Order factory.
	 * @param integer order ID
	 * @return MPaymentOrder order model
	 */
	public function taskOrder($id)
	{
		static $orders;
		if(!isset($orders[$id]))
			$orders[$id] = MPaymentOrder::model()->findByPk($id);
		return $orders[$id];
	}
	
	/**
	 * @param MPaymentOrderItem order item model
	 * @return string order item description in the following format: Credits used: {amount}
	 */
	public function taskDescription($item)
	{
		if($item->itemId == 0)
			return $this->t('Credits used: {amount}',
				array('{amount}' => m('payment')->format($item->quantity)));
		else if($item->itemId == 1)
			return $this->t('Credits purchased: {amount}',
				array('{amount}' => m('payment')->format($item->quantity)));
		return $this->t('Unknown product.');
	}
}