<?php
class BUserChangePassword extends UWorkletBehavior
{
	public function beforeBuild()
	{
		if(app()->controller->routeEased != 'user/account'
			&& !app()->user->isGuest && app()->user->model()->changePassword)
				app()->request->redirect(url('/user/account'));
	}
}