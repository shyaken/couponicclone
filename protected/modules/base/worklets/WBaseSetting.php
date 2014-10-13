<?php
class WBaseSetting extends UWidgetWorklet
{
	public $show = false;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskValidSettings()
	{
		return array(
			'language', 'ignoreMobile'
		);
	}
	
	public function taskConfig()
	{
		if(isset($_GET['name']) && isset($_GET['value']))
		{
			if(in_array($_GET['name'],$this->validSettings()))
			{
				wm()->get('base.helper')->saveToCookie($_GET['name'],$_GET['value']);
				if($_GET['name'] == 'language' && !app()->user->isGuest)
				{
					$model = app()->user->model();
					$model->language = $_GET['value'];
					$model->save();
				}
			}
		}
		if(isset($_GET['next']))
		{
			if(app()->request->isAjaxRequest)
				wm()->get('base.init')->addToJson(array('redirect' => url('/')));
			else
				app()->request->redirect(url('/'));
		}
		else
			app()->end();
	}
}