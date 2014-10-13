<?php
class BUserLanguage extends UWorkletBehavior
{
	public function beforeConfig()
	{
		if(isset($_GET['name']) && isset($_GET['value'])
			&& $_GET['name'] == 'language' && !app()->user->isGuest)
		{
			$model = app()->user->model();
			$model->language = $_GET['value'];
			$model->save();
		}
	}
}