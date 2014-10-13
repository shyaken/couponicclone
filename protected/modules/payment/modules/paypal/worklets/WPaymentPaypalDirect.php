<?php
class WPaymentPaypalDirect extends USystemWorklet
{
	public $paymentAction;
	public $formWorklet;
	
	public function run($items,$orderId=null,$options=array())
	{
		include_once(dirname(__FILE__).'/../components/paypal.nvp.class.php');
		
		$config = array(
			'APIUsername' => $this->param('apiUsername'),
			'APIPassword' => $this->param('apiPassword'),
			'APISignature' => $this->param('apiSignature'),
		);
		$config['Sandbox'] = $this->param('sandbox')?true:false;
		
		$p = new PayPal($config);
		
		$paymentDetails = array();
		$orderItems = array();
		$amount = 0;
		$total = 0;
		
		foreach($items as $key=>$val)
		{
			if(is_array($val))
			{
				if($val['name'] == $this->t('Tax'))
				{
					$paymentDetails['taxamt'] = $val['price'];
				}
				elseif($val['name'] == $this->t('Shipping Cost'))
				{
					$paymentDetails['handlingamt'] = $val['price'];
				}
				else
				{
					$item = array(
						'l_name' => urlencode($val['name']),
						'l_amt' => $val['price'],
						'l_qty' => $val['quantity'],
					);
					$orderItems[] = $item;
					$amount+= $val['price']*$val['quantity'];
				}
				$total+= $val['price']*$val['quantity'];
			}
		}
		
		$location = wm()->get('location.helper')->locationToData($this->formWorklet->model->location);
		$month = $this->formWorklet->model->ccexp['month'];
		$year = $this->formWorklet->model->ccexp['year'];
		$expdate = (strlen($month)<2?'0'.$month:$month) . $year;
		
		if($amount <= 0)
			return wm()->get('payment.checkout')->model->addError('type', $this->t('Unfortunately you can\'t pay for all order items using credits. We count items total and tax/shipping costs separately.'));
		
		$paymentDetails['amt'] = $total;
		$paymentDetails['currencycode'] = $this->module->getParentModule()->param('cCode');
		$paymentDetails['itemamt'] = $amount;
		$paymentDetails['invnum'] = $orderId;
		
		$r = $p->DoDirectPayment(array(
			'DPFields' => array(
				'paymentaction' => $this->paymentAction,
				'ipaddress' => app()->request->userHostAddress,				
			),
			'CCDetails' => array(
				'creditcardtype' => $this->formWorklet->model->cctype,
				'acct' => $this->formWorklet->model->ccnum,
				'expdate' => $expdate,
				'cvv2' => $this->formWorklet->model->cccode
			),
			'BillingAddress' => array(
				'street' => urlencode($this->formWorklet->model->address),
				'city' => urlencode($location['city']),
				'state' => $location['state'],
				'countrycode' => $location['country'],
				'zip' => $this->formWorklet->model->zip,
			),
			'PayerName' => array(
				'firstname' => urlencode($this->formWorklet->model->firstName),
				'lastname' => urlencode($this->formWorklet->model->lastName),
			),
			'PaymentDetails' => $paymentDetails,
			'OrderItems' => $orderItems
		));
		
		if($r['ACK'] == 'Success' && isset($r['CVV2MATCH']) && $r['CVV2MATCH'] == 'M')
		{
			if($this->paymentAction == 'authorization')
				wm()->get('payment.order')->authorize($orderId,$r['TRANSACTIONID']);
			else
				wm()->get('payment.order')->charge($orderId,$r['TRANSACTIONID']);
		}
		else
		{
			$w = wm()->get('payment.checkout');
			foreach($r['ERRORS'] as $error)
				$w->form->model->addError('type',$error['L_LONGMESSAGE']);
		}
	}
}