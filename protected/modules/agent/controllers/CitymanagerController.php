<?php
class CitymanagerController extends UController
{
	protected function beforeAction($action)
	{
		wm()->get('base.init')->setState('admin',true);
		return parent::beforeAction($action);
	}
	
	public function actionLogout()
	{
		app()->user->logout(false);
		app()->request->redirect(url('/agent/citymanager'));
	}
}