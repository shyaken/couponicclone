<?php
class MCompanyForm extends MCompany
{	
	public $role;
	
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('website', 'url'),
			array('phone,payment,website,zipCode,address', 'safe'),
			array('role','required','on' => 'admin'),
			array('commission','safe','on' => 'admin'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'role' => $this->t('Access Level'),
			'name' => $this->t('Name'),
			'website' => $this->t('Website'),
			'zipCode' => $this->t('ZIP'),
			'address' => $this->t('Street Address'),
			'phone' => $this->t('Phone'),
			'payment' => $this->t('Payment Account'),
			'commission' => $this->t('Commission'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}