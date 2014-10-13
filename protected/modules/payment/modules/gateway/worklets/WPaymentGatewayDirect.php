<?php
class WPaymentGatewayDirect extends USystemWorklet
{
	public $paymentAction;
	public $formWorklet;
	
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
		
		$location = wm()->get('location.helper')->locationToData($this->formWorklet->model->location);
		$month = $this->formWorklet->model->ccexp['month'];
		$year = $this->formWorklet->model->ccexp['year'];
		
		$data = array(
			'params' => $this->module->params,
			'currency' => $this->module->getParentModule()->param('cCode'),
			'cart' => $cart,
			'amount' => $amount,
			'orderId' => $orderId,
			'address' => array(
				'street' => urlencode($this->formWorklet->model->address),
				'city' => urlencode($location['city']),
				'state' => $location['state'],
				'country' => $location['country'],
				'zip' => $this->formWorklet->model->zip,
			),
			'cc' => array(
				'month' => $month,
				'year' => $year,
				'type' => $this->formWorklet->model->cctype,
				'number' => $this->formWorklet->model->ccnum,
				'code' => $this->formWorklet->model->cccode,
				'firstname' => urlencode($this->formWorklet->model->firstName),
				'lastname' => urlencode($this->formWorklet->model->lastName),
			),
		);
		
		$r = $this->paymentAction == 'authorization' && $this->param('canAuthorize')
			? $g->direct($data,'authorize')
			: $g->direct($data,'charge');
		
		if($r['status'] === false)
			wm()->get('payment.checkout')->form->model->addError('type',$r['error']);
		else
		{
			if($this->paymentAction == 'authorization')
				wm()->get('payment.order')->authorize($orderId,$r['gatewayId']);
			else
				wm()->get('payment.order')->charge($orderId,$r['gatewayId']);
		}
	}
	
	public function taskEnabled()
	{
		return $this->param('canDirect');
	}
}