<?php
class WPaymentGatewayAuthorize extends USystemWorklet
{
	public $paymentAction = 'authorization';
	
	public function accessRules()
	{
		return array(
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function run($items,$orderId=null,$options=array())
	{
		include_once(dirname(__FILE__).'/../components/Gateway.php');
		$g = new Gateway;
		
		$cart = array();
		$amount = 0;
		foreach($items as $key=>$val)
		{
			if(is_array($val))
			{
				$item = array(
					'name' => urlencode($val['name']),
					'amount' => $val['price'],
					'quantity' => $val['quantity'],
				);
				$cart[] = $item;
				$amount+= $val['price']*$val['quantity'];
			}
		}
		
		$data = array(
			'params' => $this->module->params,
			'currency' => $this->module->getParentModule()->param('cCode'),
			'cart' => $cart,
			'amount' => $amount,
			'orderId' => $orderId,
		);
		
		$r = $this->paymentAction == 'authorization' && $this->param('canAuthorize')
			? $g->authorize($data)
			: $g->charge($data);
		
		if($r['status'] === false)
			wm()->get('payment.checkout')->form->model->addError('type',$r['error']);
		else
		{
			if(app()->request->isAjaxRequest)
				wm()->get('base.init')->addToJson(array(
					'keepDisabled' => true,
					'content' => array(
						'append' => $r['form'],
						'focus' => true,
					),
				));
			else
				return $r['form'];
		}
	}
}