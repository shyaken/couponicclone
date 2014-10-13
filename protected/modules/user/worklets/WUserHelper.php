<?php
class WUserHelper extends USystemWorklet
{
	/**
	 * Registers user in the system.
	 * @param mixed form or active record model - source of data
	 * @return MUser new user model
	 */
	public function taskRegister($model)
	{
		if($model instanceOf MUser)
			$u = $model;
		else
		{
			$u = new MUser;
			$u->attributes = $model->attributes;
		}
		$u->language = app()->language;
		$u->save();
		return $u;
	}
	
	/**
	 * Automatically logges in user based on the model provided.
	 * @param MUser user model
	 */
	public function taskLogin($model)
	{
		$identity = new UUserIdentity($model->email,$model->password);
		$identity->setModel($model);
		$errorString = $identity->authenticate();
			
		if(is_string($errorString))
			return false;
		
		switch($identity->errorCode)
		{
			case UUserIdentity::ERROR_NONE:
				app()->user->login($identity,0);
				return true;
				break;
			case UUserIdentity::ERROR_USERNAME_INVALID:
				return false;
				break;
			case UUserIdentity::ERROR_PASSWORD_INVALID:
				return false;
				break;
		}
	}
}