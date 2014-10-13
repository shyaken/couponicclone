<?php
class CustomModule extends UWebModule
{
	public function preinit()
	{
		$this->setImport(array($this->getName().'.models.*'));
	}
	
	public function getVersion()
	{
		return '1.0.0';
	}
	
	public function getTitle()
	{
		return 'Custom';
	}
	
	public function getRequirements()
	{
		return array('app' => '1.1.3');
	}
	
	public function getVersionHistory()
	{
		return array(
			'1.0.0',
		);
	}
}
