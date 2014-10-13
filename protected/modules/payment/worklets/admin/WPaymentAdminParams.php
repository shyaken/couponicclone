<?php
class WPaymentAdminParams extends UParamsWorklet
{	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'placedLifetime' => array('type' => 'text',
					'label' => $this->t('"Placed" Orders Lifetime'),
					'hint' => $this->t('"Placed" orders are created initially, when user clicks "Buy" button. If placed order doesn\'t turn into authorized or paid status this usually means that user didn\'t complete the payment. But it is possible that something simply didn\'t work out and the order has been paid but got stuck in a placed status. You - as admin - can manually change it\'s status. Here you can specify for how many days should the script store "placed" orders. Put 0 to store them permanentally.')),
				'creditsOnly' => array('type' => 'radiolist', 'items' => array(
						1 => $this->t('Credits Only'),
						0 => $this->t('Normal Payment Flow')
					),
					'label' => $this->t('Site Payment Mode'),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
					'hint' => 'Select "credits only" if you want user to load funds onto their account and use those account credits to actually purchase coupons from your site.'),
				'<h4>'.$this->t('Currency').'</h4>',
				'cSymbol' => array('type' => 'text', 'label' => $this->t('Currency Symbol')),
				'cCode' => array('type' => 'text', 'label' => $this->t('Currency Code'),
					'hint' => $this->t('3-character ISO-4217 code')),
				'<p>'.$this->t('For particular reasons you may want your users to pay in a currency that is different from the site currency (for ex. when payment gateway doesn\'t support your currency). Use fields below to specify convert currency and rate.').'</p>',
				'convertCode' => array('type' => 'text', 'label' => $this->t('Convert To (currency code)'),
					'hint' => $this->t('3-character ISO-4217 code; leave empty to disable convertion')),
				'convertRate' => array('type' => 'text', 'label' => $this->t('Convertion Rate'), 'hint' => 'site currency / convert currency = ?'),
				'convertMethods' => array('type' => 'checkboxlist', 'items' => $this->methods(),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
					'label' => $this->t('Convert For')),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function taskRenderOutput()
	{
		parent::taskRenderOutput();
		
		$modules = $this->module->getModules();
		foreach($modules as $id=>$cfg)
		{
			$m = $this->module->getModule($id);
			if($m)
			{
				echo '<h4>'. ucfirst($m->getTitle()) .'</h4>';
				app()->controller->worklet('payment.' . $id . '.admin.params');
			}
		}
	}
	
	public function beforeSave()
	{
		if(is_array($this->model->convertMethods))
			$this->model->convertMethods = implode(',',$this->model->convertMethods);
		else
			$this->model->convertMethods = '';
	}
	
	public function afterConfig()
	{
		$this->model->convertMethods = explode(',',$this->model->convertMethods);
	}
	
	public function taskMethods()
	{
		$modules = app()->modules['payment']['modules'];
		$methods = array();
		foreach($modules as $name=>$config)
			$methods[$name] = $config['params']['name'];
		return $methods;
	}
}