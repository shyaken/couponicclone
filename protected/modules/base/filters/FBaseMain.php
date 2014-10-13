<?php
class FBaseMain extends UWorkletFilter
{	
	public function filters()
	{
		return array(
			'base.init' => array('behaviors' => 'BaseInit'),
		);
	}
	
	public function BaseOptimize()
	{
		if(!YII_DEBUG && file_exists($this->module->basePath.DS.'behaviors'.DS.'BBaseOptimize.php'))
			return array('base.optimize');
		return array();
	}
	
	public function BaseInit()
	{
		return CMap::mergeArray($this->BaseOptimize(),array(
			'base.rules'
		));
	}
}