<?php
class MDealPartnerForm extends UFormModel
{
	public $companyName;
	public $email;
	public $firstName;
	public $lastName;
	public $address;
	public $phone;
	public $website;
	public $reviews;
	public $about;
	
	public $location;
	
	public static function module()
	{
		return 'deal';
	}
	
	public function rules()
	{
		return array(
			array('companyName,firstName,lastName,email,address,phone','required'),
			array('reviews,about,website', 'safe'),
			array('website','url'),
			array('email', 'email'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'companyName' => $this->t('Business Name'),
			'firstName' => $this->t('First Name'),
			'lastName' => $this->t('Last Name'),
			'email' => $this->t('Email Address'),
			'address' => $this->t('Address'),
			'phone' => $this->t('Phone Number'),
			'website' => $this->t('Website'),
			'reviews' => $this->t('Review Link(s)'),
			'about' => $this->t('Tell us a little bit about your business'),
		);
	}
	
}