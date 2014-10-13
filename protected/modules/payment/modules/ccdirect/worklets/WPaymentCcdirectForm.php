<?php
class WPaymentCcdirectForm extends UFormWorklet
{
	public function properties()
	{
		static $p;
		if(!isset($p))
		{
			$b = UFactory::getBehavior('payment.ccdirect.form');
			$p = $b->properties();
			$p = $p['ccdirect'];
			$this->model = $p['model'];
		}
		return $p;
	}
	
	public function beforeBuild()
	{
		$b = $this->attachBehavior('location.form','location.form');
		$b->ignoreFixed = true;
	}
	
	public function taskSuccess()
	{
		return;
	}
}