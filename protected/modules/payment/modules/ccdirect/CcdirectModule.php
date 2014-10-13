<?php
class CcdirectModule extends UWebModule
{
	public function getTitle()
	{
		return 'Credit Card';
	}
	
	public function getRequirements()
	{
		return array('payment' => self::getVersion());
	}
}
