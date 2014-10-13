<?php
class MCitymanagerLoginForm extends UFormModel
{
	public $email;
	public $password;
	public $rememberMe;
	
	public static function module()
	{
		return 'agent';
	}
	
	public function rules()
	{
		return array(
			array('email,password', 'required'),
			array('rememberMe', 'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'email' => $this->t('Email'),
			'password' => $this->t('Password'),
			'rememberMe' => $this->t('Remember Me')
		);
	}
}