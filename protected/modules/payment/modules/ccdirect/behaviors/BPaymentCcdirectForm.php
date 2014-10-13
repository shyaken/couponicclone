<?php
class BPaymentCcdirectForm extends UWorkletBehavior
{
	public $modelClassName = 'MCcdirectForm';
	
	public function afterBuild()
	{
		if(!is_array($this->owner->paymentTypes)
			|| in_array('ccdirect',$this->owner->paymentTypes))
				wm()->get('base.init')->requireSecure = true;
	}
	
	public function properties()
	{
		return array(
			'<div id="ccdirect" class="paymentForm" style="display: none">',
			'ccdirect' => array('type' => 'UForm',
				'elements' => array(
					'cctype' => array('type' => 'dropdownlist', 'items' => array(
						'Visa' => 'Visa',
						'MasterCard' => 'MasterCard',
						'Discover' => 'Discover',
						'Amex' => 'Amex',
					)),
					'ccnum' => array('type' => 'text'),
					'ccexp' => array('type' => 'ccdirect.components.UCCExpDate'),
					'cccode' => array('type' => 'text', 'class' => 'short'),
					'firstName' => array('type' => 'text'),
					'lastName' => array('type' => 'text'),
					'address' => array('type' => 'text'),
					'zip' => array('type' => 'text'),
				),
				'model' => $this->model(),
			),
			'</div>',
		);
	}
	
	public function model()
	{
		static $m;
		if(!isset($m))
		{
			$m = new MCcdirectForm;
			$m->location = wm()->get('deal.helper')->location();
		}
		return $m;
	}
	
	public function afterConfig()
	{
		$this->getOwner()->insertAfter('type',$this->properties());
	}
	
	public function afterModel()
	{
		$b = $this->getOwner()->attachBehavior('location.form','location.form');
		$b->model = $this->model();
		$b->elementsKey = array('ccdirect');
		$b->ignoreFixed = true;
		
		$this->getOwner()->attachBehavior('payment.ccdirect.form','payment.ccdirect.form');
	}
	
	public function afterCreateForm()
	{
		if($this->owner->model->type == 'ccdirect' && $this->owner->amount>0)
			$this->model()->scenario = 'cc';
	}
}