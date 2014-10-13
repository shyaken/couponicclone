<?php
class WPaymentSuccess extends UWidgetWorklet
{
	public $successUrl = '';
	public $failUrl = '';
	
	public function title()
	{
		return $this->t('Thank you for placing your order!');
	}
	
	public function accessRules()
	{
		return array(
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function taskConfig()
	{
		$this->successUrl = url('/');
		$this->failUrl = url('/');
		wm()->get('payment.cart')->empty();
		parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{
		$id = app()->request->getParam('id', null);
		if($id)
		{
			$order = wm()->get('payment.order')->order($id);
			if($order && $order->status > 0 && $order->status < 3)
			{
				$product = array('id' => 0, 'price' => 0, 'quantity' => 0);
				foreach($order->items as $i)
				{
					if($i->itemModule == 'deal')
					{
						$price = MDealPrice::model()->findByPk($i->itemId);
						$product['id'] = $price->deal->id;
						$product['price'] = $price->deal->price;
						$product['quantity'] = $i->quantity;
						break;
					}
				}
				
				$data = array(
					'{orderId}' => $id,
					'{amount}' => $order->amount,
					'{productId}' => $product['id'],
					'{price}' => $product['price'],
					'{quantity}' => $product['quantity'],
					'{gateway}' => $order['method'],
				);
				
				$codes = MPaymentAffiliate::model()->findAll();				
				return $this->render('success', array('codes' => $codes, 'data' => $data));
			}
			else
			{
				$this->title = $this->t('Unfortunately your order has failed.');
				$this->render('fail');		
			}
		}
		$this->render('success', array('codes' => array(), 'data' => array()));
	}
}