<?php
class MDealSubscribeForm extends UFormModel
{
	public $location;
	public $email;
	public $category;
	
	public function rules()
	{
		return array(
			array('email', 'required'),
			array('email', 'email'),
			array('location,category', 'safe'),
		);
	}
}