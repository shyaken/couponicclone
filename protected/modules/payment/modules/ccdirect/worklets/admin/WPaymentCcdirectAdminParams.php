<?php
class WPaymentCcdirectAdminParams extends UParamsWorklet
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
				'gateway' => array('type' => 'radiolist', 'label' => $this->t('Gateway'),
					'items' => $this->gateways(), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function taskGateways()
	{
		$modules = app()->modules['payment']['modules'];
		$gateways = array();
		foreach($modules as $name=>$config)
		{
			$w = wm()->get('payment.'.$name.'.direct');
			if($w)
				$gateways[$name] = $config['params']['name'];
		}
		return $gateways;
	}
}