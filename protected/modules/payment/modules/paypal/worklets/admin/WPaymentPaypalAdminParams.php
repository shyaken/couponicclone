<?php
class WPaymentPaypalAdminParams extends UParamsWorklet
{
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return;
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'name' => array('type' => 'text', 'label' => $this->t('Name')),
				'business' => array('type' => 'text', 'label' => $this->t('Account Email Address')),
				'method' => array('type' => 'radiolist', 'label' => $this->t('Method'), 'items' => array(
					'standard' => $this->t('Paypal Standard'),
					'expressCheckout' => $this->t('Express Checkout'),
				), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
				'sandbox' => array('type' => 'radiolist', 'label' => $this->t('Sandbox'),
					'items' => array(
						0 => $this->t('Disable'),
						1 => $this->t('Enable')
					), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"
				),
				'cconly' => array('type' => 'radiolist', 'label' => $this->t('Use As'),
					'items' => array(
						1 => $this->t('Gateway for direct credit card payments only'),
						0 => $this->t('Gateway and standalone payment method')
					), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"
				),
				'<h4>'.$this->t('PayPal API Credentials').'</h4>',
				$this->render('apiInfo',null,true),
				'apiUsername' => array('type' => 'text', 'label' => $this->t('API Username')),
				'apiPassword' => array('type' => 'text', 'label' => $this->t('API Password')),
				'apiSignature' => array('type' => 'text', 'label' => $this->t('API Signature')),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
}