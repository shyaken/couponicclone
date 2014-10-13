<?php
class DealModule extends UWebModule
{
	public function getTitle()
	{
		return 'Deal';
	}
	
	public function getRequirements()
	{
		return array('company' => self::getVersion());
	}
}
