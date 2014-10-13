<?php
class WDealInfo extends UWidgetWorklet
{
	public $deal;
	public $position = array('after'=>'deal.view');
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskConfig()
	{
		if(!$this->deal)
			return $this->show = 0;	
	}
	
	public function taskRenderOutput()
	{
		$this->render('info');
	}
}