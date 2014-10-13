<?php
class WPaymentGatewayAdminParams extends UParamsWorklet
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
				'test' => array('type' => 'radiolist', 'label' => $this->t('Test Mode'),
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
				'<h4>Settings</h4>',
				'canAuthorize' => array(
					'type' => 'checkbox',
					'layout' => "<fieldset>{input}\n{label}\n{hint}</fieldset>",
					'uncheckValue' => '',
					'required' => false,
					'afterLabel' => '',
					'label' => $this->t('Payment provider supports "authorize" & "capture" technology and I can authorize the payment without actually charging the user and later "capture" it.'),
				),
				'canVoid' => array(
					'type' => 'checkbox',
					'layout' => "<fieldset>{input}\n{label}\n{hint}</fieldset>",
					'uncheckValue' => '',
					'required' => false,
					'afterLabel' => '',
					'label' => $this->t('Payment provider supports "authorize" & "capture" technology and I can void previously authorized payment.'),
				),
				'canRefund' => array(
					'type' => 'checkbox',
					'layout' => "<fieldset>{input}\n{label}\n{hint}</fieldset>",
					'uncheckValue' => '',
					'required' => false,
					'afterLabel' => '',
					'label' => $this->t('Payment provider allows to refund any order via API calls.'),
				),
				'canDirect' => array(
					'type' => 'checkbox',
					'layout' => "<fieldset>{input}\n{label}\n{hint}</fieldset>",
					'uncheckValue' => '',
					'required' => false,
					'afterLabel' => '',
					'label' => $this->t('Payment provider allows me to collect users credit card info directly on my site and send it in the background via API calls.'),
				),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
}