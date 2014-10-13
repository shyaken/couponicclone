<?php
class PaypalModule extends UWebModule
{	
	public function getTitle()
	{
		return 'Paypal';
	}
	
	public function getRequirements()
	{
		return array('payment' => self::getVersion());
	}
}
