<?php
class WDealOrder extends USystemWorklet
{
	/**
	 * Verifies the deal order.
	 * @param integer deal ID
	 * @param integer ordered quantity
	 * @return boolean true or error message as a string
	 */
	public function taskVerify($id,$quantity,$user=null)
	{
		// price
		$price = MDealPrice::model()->findByPk($id);
		if(!$price)
			return $this->t('Deal not found.');
		
		$deal = $price->deal;
		if(!$deal)
			return $this->t('Deal not found.');
			
		// if $user is provided - this is re-verification
		// we need to proceed even if the deal is not available anymore
		if(!$user && !wm()->get('deal.helper')->available($deal))
			return $this->t('You can\'t order coupons for deal "{name}". It is either over or cancelled.',array(
				'{name}' => $deal->name
			));
		
		$user = $user?$user:(app()->user->isGuest?null:app()->user->model());
			
		if($user)
		{
			// get coupons which user has already bought for this deal
			$userCoupons = wm()->get('deal.helper')->userCoupons($deal, $user->id);
			
			// check if current order will break any of deal limits
			if($deal->limitPerUser
				&& $deal->limitPerUser < ($userCoupons + $quantity))
			{
				$left = $deal->limitPerUser - $userCoupons;
				if($left>0)
					return $this->t('Sorry, you can order only {num} coupons for this deal.', array(
						'{num}' => $left
					));
				else
					return $this->t('Sorry, you can\'t order anymore coupons for this deal.');
			}
		}
		
		if($deal->stats && $deal->purchaseMax
			&& $deal->purchaseMax < $deal->stats->bought+$quantity)
		{
			$left = $deal->purchaseMax - $deal->stats->bought;
			if($left>0)
				return $this->t('Sorry, you can order only {num} coupons for this deal.', array(
					'{num}' => $left
				));
			else
				return $this->t('Sorry, you can\'t order anymore coupons for this deal.');
		}
		
		// all limits are respected - add item to the checkout items stack
		
		$status = wm()->get('deal.helper')->dealStatus($deal);

		$checkout = wm()->get('payment.checkout');
		$checkout->amount+= $deal->price * $quantity;
		$checkout->items[] = array(
			'name' => $deal->name,
			'quantity' => $quantity,
			'price' => $deal->price
		);
		
		// if the deal is tipped already - charge user immediately, authorize otherwise
		$checkout->transactionType = $status == 'tipped' ? 'pay' : 'authorize';
		
		// push deal's payment options
		if($deal->paymentOptions)
		{
			$opts = explode(':',$deal->paymentOptions);
			$checkout->paymentTypes = is_array($checkout->paymentTypes)
				? array_intersect($checkout->paymentTypes,$opts)
				: $opts;
		}
		
		return true;
	}
	
	/**
	 * Does necesssary actions if the order has been authorized.
	 * @param MPaymentOrder order model
	 * @param MPaymentOrderItem order item model
	 */
	public function taskAuthorized($order,$item)
	{
		// find price
		$price = MDealPrice::model()->findByPk($item->itemId);
		// find deal
		$deal = $price->deal;
		
		// create coupons
		$i = $item->quantity;
		while($i--)
			$this->coupon($order,$item);
		
		// update deal stats
		$deal->stats = $deal->stats?$deal->stats:wm()->get('deal.helper')->dealStats($deal->id);
		$deal->stats->bought = MDealCoupon::model()->count('dealId=?', array($deal->id));
		$deal->stats->save();
		
		// ping deal
		wm()->get('deal.helper')->newOrder($deal,$item);
		
		// send email if not tipped
		if(wm()->get('deal.helper')->dealStatus($deal) != 'tipped')
			app()->mailer->send($order->user, 'authorizedEmail', array('deal' => $deal));
			
		// subscribe user to deal related list
		wm()->get('subscription.helper')->addEmailToList($order->user->email,array(
			'type' => 1, 'relatedId' => $deal->id
		));
	}
	
	/**
	 * Does necesssary actions if the order has been charged.
	 * @param MPaymentOrder order model
	 * @param MPaymentOrderItem order item model
	 */
	public function taskCharged($order,$item)
	{
		// find price
		$price = MDealPrice::model()->findByPk($item->itemId);
		// find deal
		$deal = $price->deal;
		
		// send email if tipped
		if(wm()->get('deal.helper')->dealStatus($deal) == 'tipped')
			app()->mailer->send($item->order->user, 'chargedEmail', array('deal' => $deal));
	}
	
	/**
	 * Does necesssary actions if the order has been voided/refunded.
	 * @param MPaymentOrder order model
	 * @param MPaymentOrderItem order item model
	 */
	public function taskVoided($order,$item)
	{
		// find price
		$price = MDealPrice::model()->findByPk($item->itemId);
		// find deal
		$deal = $price->deal;
		
		// delete coupons
		MDealCoupon::model()->deleteAll('orderId=?',array($order->id));
		
		// update deal stats
		$deal->stats = $deal->stats?$deal->stats:wm()->get('deal.helper')->dealStats($deal->id);
		$deal->stats->bought = MDealCoupon::model()->count('dealId=?', array($deal->id));
		$deal->stats->save();
		
		// is this deal still tipped
		wm()->get('deal.helper')->verifyTipped($deal);
	}
	
	/**
	 * Charges all orders associated with a deal.
	 * @param integer deal ID
	 * @return array successful and failed charges counts
	 */
	public function taskChargeDeal($id)
	{
		$pmodels = MDealPrice::model()->findAll('dealId=?', array($id));
		$prices = array();
		foreach($pmodels as $m)
			$prices[] = $m->id;
		
		$c = new CDbCriteria;
		$c->with = array('items');
		$c->condition = 't.status=:status AND items.itemModule=:module';
		$c->params = array(':status' => 1, ':module' => 'deal');
		$c->addInCondition('items.itemId', $prices);
		
		$models = MPaymentOrder::model()->findAll($c);
		
		$success = 0;
		$fail = 0;
		foreach($models as $m)
		{
			$w = wm()->get('payment.' . $m->method . '.charge');
			if($w && $w->run($m))
				$success++;
			else
				$fail++;
		}
		return array($success,$fail);
	}
	
	/**
	 * Voids/refunds all order associated with a deal.
	 * @param integer deal ID
	 * @return array successful and failed voids/refunds counts
	 */
	public function taskCancelDeal($id)
	{
		$pmodels = MDealPrice::model()->findAll('dealId=?', array($id));
		$prices = array();
		foreach($pmodels as $m)
			$prices[] = $m->id;
		
		$c = new CDbCriteria;
		$c->with = array('items');
		$c->condition = '(t.status=:s1 OR t.status=:s2) AND items.itemModule=:module';
		$c->params = array(':s1' => 1, ':s2' => 2, ':module' => 'deal');
		$c->addInCondition('items.itemId', $prices);
		
		$models = MPaymentOrder::model()->findAll($c);
		
		$success = 0;
		$fail = 0;
		foreach($models as $m)
		{
			if($m->status == 1)
				$r = wm()->get('payment.' . $m->method . '.void')->run($m);
			else
				$r = wm()->get('payment.' . $m->method . '.refund')->run($m);
				
			if($r)
			{
				$deal = MDeal::model()->findByPk($id);
				app()->mailer->send($m->user, 'cancelDeal', array('deal' => $deal));
				$success++;
			}
			else
				$fail++;        
		}
		return array($success,$fail);
	}
	
	/**
	 * Creates a coupon.
	 * @param MPaymentOrder order model
	 * @param MPaymentOrderItem order item model
	 * @return MDealCoupon coupon model
	 */
	public function taskCoupon($order,$item)
	{
		$price = MDealPrice::model()->findByPk($item->itemId);
		
		$code = $this->redemptionCode(10);
		$m = new MDealCoupon;
		$m->orderId = $order->id;
		$m->userId = $order->userId;
		$m->dealId = $price->deal->id;
		$m->priceId = $price->id;
		$m->hash = strtoupper(UHelper::hash('MDealCoupon',12));
		$m->redemptionCode = $code;
		$option = MPaymentOrderOptions::model()->find('itemId=? AND type=?', array($item->id, 'redeemLocation'));
		$m->redeemLocationId = $option ? $option->value : null;
		$m->save();
		return $m;
	}
	
	public function taskRedemptionCode($length=10)
	{
		list($usec, $sec) = explode(' ', microtime());
  		mt_srand((float) $sec + ((float) $usec * 100000));
  		$min = str_pad('1',$length,'0') * 1;
  		$max = str_pad('9', $length, '9') * 1;
  		
  		$exists = 1;
		while($exists)
		{
			$code = mt_rand($min, $max);
			$exists = MDealCoupon::model()->exists('redemptionCode=?',array($code));
		}
  		return $code;
	}
	
	/**
	 * @param MPaymentOrderItem order item model
	 * @return string order item description in the following format: Deal: {dealName} ({quantity})
	 */
	public function taskDescription($item)
	{
		$price = MDealPrice::model()->findByPk($item->itemId);
		$deal = $price->deal;
		return $this->t('Deal').': '.$deal->name.' ('.$item->quantity.')';
	}
	
	public function taskDescriptionInCart($item)
	{
		$price = MDealPrice::model()->findByPk($item['id']);
		$deal = $price->deal;		
		$loc = wm()->get('deal.helper')->currentLocation($price->id);
		return $deal->name.($deal->requireRedeemLoc && count($deal->redeemLocs) && $loc ? '<br />' . CHtml::link($loc, array('/deal/loc', 'id' => $price->id), array('class' => 'uDialog', 'id'=>'LocationDialog-'.$price->id)):'');
	}
}