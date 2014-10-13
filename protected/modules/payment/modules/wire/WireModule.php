<?php
class WireModule extends UWebModule
{
	public function getTitle()
	{
		return 'Bank Wire';
	}
	
	public function getRequirements()
	{
		return array('payment' => self::getVersion());
	}
}
