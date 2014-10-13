<?php
class WPaymentPaypalAuthorize extends USystemWorklet
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
		switch($this->module->param('method'))
		{
			case 'standard':
				return $this->standard($items,$orderId,$options);
				break;
			case 'expressCheckout':
				return $this->expressCheckout($items,$orderId,$options);
				break;
		}
	}	
	
	public function taskExpressCheckout($items,$orderId=null,$options=array())
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
				elseif($val['name'] == $this->t('Shipping'))
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
		
		if($amount <= 0)
			return wm()->get('payment.checkout')->model->addError('type', $this->t('PayPal wouldn\'t allow to pay for all order items using credits. It counts items and tax/shipping costs separately.'));
		
		$paymentDetails['amt'] = $total;
		$paymentDetails['currencycode'] = $this->module->getParentModule()->param('cCode');
		$paymentDetails['itemamt'] = $amount;
		$paymentDetails['custom'] = $orderId;
		
		$result = $p->SetExpressCheckout(array(
			'SECFields' => array(
				'returnurl' => aUrl('/payment/paypal/exch', array('paymentaction' => $this->paymentAction)),
				'cancelurl' => isset($options['cancel_return'])?$options['cancel_return']:aUrl('/'),
				'paymentaction' => $this->paymentAction,
				'skipdetails' => '1',
			),
			'PaymentDetails' => $paymentDetails,
			'OrderItems' => $orderItems
		));
		
		wm()->get('base.init')->addToJson(array(
			'redirect' => $result['REDIRECTURL'],
		));
	}
	
	public function taskStandard($items,$orderId=null,$options=array())
	{		
		$options['business'] = isset($options['business'])?$options['business']:$this->param('business');
				
		$options['return'] = isset($options['return'])?$options['return']:aUrl('/payment/success', array('id' => $orderId));
		$options['cancel_return'] = isset($options['cancel_return'])?$options['cancel_return']:aUrl('/');
		$options['notify_url'] = isset($options['notify_url'])?$options['notify_url']:aUrl('/payment/paypal/ipn');
		
		if($orderId)
			$options['custom'] = $orderId;
			
		$options['currency_code'] = $this->module->getParentModule()->param('cCode');
		$options['paymentaction'] = $this->paymentAction;
		
		$discount = 0;
		$amount = 0;
		
		include_once(dirname(__FILE__).'/../components/paypal.ipn.class.php');
		$p = new paypal_class;
		$p->ipn_log = false;
		
		foreach($options as $k=>$v)
			$p->add_field($k,$v);
			
		foreach($items as $key=>$val)
		{
			if($val['price'] < 0)
			{
				$p->add_field('discount_amount_cart',-($val['price']*$val['quantity']));
				$discount = $val['price']*$val['quantity'];
			}
			elseif($val['name'] == $this->t('Tax'))
				$p->add_field('tax_cart',$val['price']);
			elseif($val['name'] == $this->t('Shipping'))
				$p->add_field('handling_cart',$val['price']);
			elseif(is_array($val))
			{
				$amount+= $val['price'] * $val['quantity'];
				foreach($val as $k=>$v)
				{
					$paypalKey = '';
					switch($k)
					{
						case 'name':
							$paypalKey = 'item_name';
							break;
						case 'price':
							$paypalKey = 'amount';
							break;
						case 'quantity':
							$paypalKey = 'quantity';
							break;
					}
					if($paypalKey)
					{
						$paypalKey.= '_'.($key+1);
						$p->add_field($paypalKey,$v);
					}
				}
			}
		}
		
		if($discount >= $amount)
			return wm()->get('payment.checkout')->model->addError('type', $this->t('PayPal wouldn\'t allow to pay for all order items using credits. It counts items and tax/shipping costs separately.'));
			
		if($this->param('sandbox'))
			$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			
		$p->add_field('cmd','_cart');
		$p->add_field('upload','1');
		$p->add_field('charset','utf-8');
		
		$html = CHtml::beginForm($p->paypal_url,'post',array('id'=>'paymentForm', 'class' => 'form', 'style'=>'display:none'));
		foreach ($p->fields as $name => $value)
			$html.= CHtml::hiddenField($name,$value);
		$html.= CHtml::submitButton('',array('class' => 'submit', 'id' => 'paymentFormSubmit'));
		$html.= CHtml::endForm();
		$html.= CHtml::script('jQuery("#paymentFormSubmit").click();');
		
		if(app()->request->isAjaxRequest)
			wm()->get('base.init')->addToJson(array(
				'keepDisabled' => true,
				'content' => array(
					'append' => $html,
					'focus' => true,
				),
			));
		else
			return $html;
	}
}