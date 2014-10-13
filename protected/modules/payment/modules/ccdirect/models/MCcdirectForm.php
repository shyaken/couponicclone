<?php
class MCcdirectForm extends UFormModel
{
	public $cctype;
	public $ccnum;
	public $ccexp;
	public $cccode;
	
	public $firstName;
	public $lastName;
	
	public $address;
	public $location;
	public $zip;
	
	public static function module()
	{
		return 'payment.ccdirect';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe'),
			array(implode(',',array_keys(get_object_vars($this))),'required', 'on' => 'cc')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'cctype' => $this->t('Card Type'),
			'ccnum' => $this->t('Card Number'),
			'ccexp' => $this->t('Expiration Date'),
			'cccode' => $this->t('CVV2 Code'),
			'firstName' => $this->t('First Name'),
			'lastName' => $this->t('Last Name'),
			'address' => $this->t('Address'),
			'zip' => $this->t('ZIP/Postal Code'),
		);
	}
}