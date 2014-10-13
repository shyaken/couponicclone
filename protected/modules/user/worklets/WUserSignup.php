<?php
class WUserSignup extends UFormWorklet
{
	public $modelClassName = 'MUserSignupForm';
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function title()
	{
		return $this->t('Sign Up');
	}
	
	public function properties()
	{
		return array(
			'action' => url('/user/signup', array('dialog' => isset($_GET['dialog']) ? $_GET['dialog'] : 0)),
			'elements' => array(
				'firstName' => array('type' => 'text'),
				'lastName' => array('type' => 'text'),
				'email' => array('type' => 'text'),
				'password' => array('type' => 'password'),
				'passwordRepeat' => array('type' => 'password'),
				'profile' => wm()->get('user.profile.helper')->form(),
				'<hr />',
				'termsAgree' => array(
					'type' => 'checkbox',
					'layout' => "<fieldset>{input}\n{label}\n{hint}</fieldset>",
					'uncheckValue' => '',
					'required' => false,
					'afterLabel' => '',
				),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Sign Up'))
			),
			'model' => $this->model
		);
	}
	
	public function beforeBuild()
	{
		if($this->param('captcha'))
			$this->attachBehavior('base.captcha','base.captcha');
	}
	
	public function taskConfig()
	{
		parent::taskConfig();			
		wm()->add('base.dialog');
	}
	
	public function taskSave()
	{
		$this->model->timeZone = app()->param('timeZone');
		// register user
		$user = wm()->get('user.helper')->register($this->model);
		
		if($this->getModule()->params['emailVerification']){
			// generate verification hash
			$hash = UHelper::hash();
			// save hash in a DB
			$model = new MHash;
			$model->hash = $hash;
			$model->type = 1;
			$model->id   = $user->id;
			$model->expire = $this->getModule()->params['verificationTimeLimit']
				? time() + $this->getModule()->params['verificationTimeLimit'] * 3600
				: 0;
			$model->save();
			
			// send verification email
			app()->mailer->send($user, 'verificationEmail', array('link' => aUrl(
				'/user/verify', array(
					'h' => $hash,
					'e' => $user->email,
				),'http'))
			);
		}
		
		if(($this->param('emailVerification')=='0' || $this->param('unverifiedAccess')=='0')
			&& ($this->param('approveNewAccounts')=='0' || $this->param('unapprovedAccess')=='0'))
		{
			$identity=new UUserIdentity($user->email,$user->password);
			$identity->setModel($user);
			$identity->authenticate();
			if($identity->errorCode === UUserIdentity::ERROR_NONE)
				app()->user->login($identity,0);
			$this->successUrl = app()->user->returnUrl?app()->user->returnUrl:$this->successUrl;
		}
		
		$flash = $this->t('Your account has been successfully created!');
		switch($user->role)
		{
			case 'unverified':
				$flash.= ' ' . $this->t('Please follow instructions from our email to verify your account.');
				break;
			case 'unapproved':
				$flash.= ' ' . $this->t('You will be able to login and start using your account once it will be approved by admin.');
				break;
		}
		
		app()->user->setFlash('info', $flash);
		
		if(app()->user->isGuest && !m('base')->param('publicAccess'))
			$this->successUrl = url('/user/login');
		
		return true;
	}
	
	public function afterSave()
	{
		if(isset($this->properties['elements']['profile']['model']))
			foreach($this->properties['elements']['profile']['model']->attributes as $k=>$v)
			{
				if(strpos($k,'_')===false)
					continue;
				
				list($dummy,$id) = explode('_', $k);
				if($id)
				{
					$m = new MUserProfile;
					$m->settingId = $id;
					$m->userId = $this->model->id;
					$m->value = $v;
					$m->save();
				}
			}
	}
}