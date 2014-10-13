<?php
class MCompanyUserForm extends MUserAccountForm
{
	public $newPassword;
	public $passwordRepeat;
	
	public function rules()
	{
		return array(
			array('firstName,email','required'),
			array('password','required','on' => 'newUser'),
			array('firstName,lastName,email', 'length', 'max' => 250),
			array('avatar,timeZone,password', 'safe'),
			array('email', 'email'),
			array('newPassword', 'length', 'min' => 6),
			array('passwordRepeat', 'compare', 'compareAttribute' => 'newPassword'),
			array('role','safe','on'=>'admin, newUser'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}