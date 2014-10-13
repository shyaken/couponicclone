<?php
class WPaymentRemove extends UWidgetWorklet
{
	public $layout = false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'users'=>array('*'))
		);
	}
	
	public function taskConfig()
	{
		if(!isset($_GET['id'], $_GET['module']))
			$this->accessDenied();
		wm()->get('payment.cart')->remove($_GET['module'],$_GET['id']);
	}
	
	public function taskRenderOutput()
	{
		echo CHtml::script('$("#items_'.$_GET['module'].'_'.$_GET['id'].'").closest("tr").remove();');
	}
}