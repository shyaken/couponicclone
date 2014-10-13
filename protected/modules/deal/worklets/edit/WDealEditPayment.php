<?php
class WDealEditPayment extends UFormWorklet
{
	public $modelClassName = 'MDealPaymentOptionsForm';
	public $primaryKey='id';
	
	public function title()
	{
		return $this->t('{title}: Payment Options', array(
			'{title}' => $this->deal()->name
		));
	}
	
	public function taskDeal()
	{
		return MDeal::model()->findByPk($_GET['id']);
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeAccess()
	{
		if(isset($_GET['id']) && wm()->get('deal.edit.helper')->authorize($_GET['id']))
			return true;
		$this->accessDenied();
		return false;
	}
	
	public function properties()
	{		
		return array(
			'elements' => array(
				'paymentOptions' => array('type' => 'checkboxlist', 
					'items' => $this->paymentOptions(),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
					'hint' => $this->t('Leave blank to enable all currently available payment methods.')),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Add'):$this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function afterConfig()
	{
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('Payment Options');
		return $r;
	}
	
	public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array(
			'info' => array(
				'replace' => $this->t('Deal has been successfully updated.'),
				'fade' => 'target',
				'focus' => true,
			),
		));
	}
	
	public function paymentOptions()
	{
		$modules = app()->modules['payment']['modules'];
		$types = array();
		foreach($modules as $name=>$config)
			if((!isset($config['enabled']) || $config['enabled'])
				&& (!isset($config['params']['cconly']) || !$config['params']['cconly']))
				$types[$name] = $config['params']['name'];
		return $types;
	}
}