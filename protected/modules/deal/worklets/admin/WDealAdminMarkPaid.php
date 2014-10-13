<?php
class WDealAdminMarkPaid extends UWidgetWorklet
{
	public $show = false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskConfig()
	{
		if(isset($_GET['id']))
		{
			$model = MDeal::model()->findByPk($_GET['id']);
			if($model)
			{
				$model->status = 3;
				$model->save();
			}
		}
	}
}