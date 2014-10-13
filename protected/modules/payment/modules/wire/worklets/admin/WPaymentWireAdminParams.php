<?php
class WPaymentWireAdminParams extends UParamsWorklet
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
				'info' => array('type' => 'UCKEditor', 'label' => $this->t('Information'), 
					'hint' => $this->t('Payment instructions. You can use {orderID} and {amount} patterns which will be replaced by the actual order ID and amount. You can later use it to find the order in admin -> orders to mark it as paid (when you receive the payment).'),
					'layout' => "<div class='clearfix'>{label}</div>{input}\n{hint}"),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function beforeSave()
	{
		$purifier = new CHtmlPurifier;
		$purifier->options = array(
			'Attr.AllowedFrameTargets' => array('_blank','_self','_parent','_top')
		);
		$this->model->info = $purifier->purify($this->model->info);
	}
}