<?php
class WBaseContact extends UWidgetWorklet
{
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function title()
	{
		return $this->t('Custom Module: Test Worklet');
	}
	
	public function taskRenderOutput()
	{
		$this->render('test');
	}
}