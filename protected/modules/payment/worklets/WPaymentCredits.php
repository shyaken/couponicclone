<?php
class WPaymentCredits extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	public $checkOutForm;
	
	public function title()
	{
		return $this->t('Add Credits');
	}
	
	public function afterConfig()
	{
		$this->checkOutForm = wm()->get('payment.checkout');
		$this->checkOutForm->forceShow = true;
		$this->checkOutForm->init();		
	}
	
	public function taskRenderOutput()
	{
		$this->render('paymentCredit');
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'attribute' => array('type' => 'text' , 'label' => $this->t('How much credits would you like to add'),
					'attributes'=> array('name' => 'items[payment][1]', 'class' => 'short'),
				),
			),
			'model' => $this->model
		);
	}
}