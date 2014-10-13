<?php
class WApiLogin extends UApiWorklet
{
	public function taskLogin(){
	
		$email = $this->getRequiredParam('email');
		$password = $this->getRequiredParam('password');
		$rememberme = app()->request->getParam('rememberme',null);
		$type = app()->request->getParam('type',null);
		
		$identity=new UUserIdentity($email,$password);
		$errorString = $identity->authenticate();

		if(is_string($errorString))
			$this->errorMessage = $errorString;

		switch($identity->errorCode)
		{
			case UUserIdentity::ERROR_NONE:
				$duration=!is_null($rememberme) ? 3600*24*30 : 0; // 30 days
				app()->user->login($identity,$duration);
				break;
			case UUserIdentity::ERROR_USERNAME_INVALID:
				$this->errorMessage = $this->t('Email or password is incorrect.');
				break;
			case UUserIdentity::ERROR_PASSWORD_INVALID:
				$this->errorMessage = $this->t('Email or password is incorrect.');
				break;
		}
		
		if(!is_null($type) && $type == 'merchant' && !app()->user->checkAccess('company',array(),false))
			$this->errorMessage = $this->t('Email or password is incorrect.');
	
	}
	
	public function afterConfig()
	{
		$this->login();

		$data[]['session_name'] = Yii::app()->getSession()->getSessionName();
		$data[]['session'] = Yii::app()->getSession()->getSessionID();
		
			
		$this->data = $data;
	}
	
}

