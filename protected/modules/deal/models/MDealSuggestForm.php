<?php
class MDealSuggestForm extends UFormModel
{
	public $name;
	public $website;
	public $location;
	public $review;
	
	public static function module()
	{
		return 'deal';
	}
	
	public function rules()
	{
		return array(
			array('name','required'),
			array('website,review', 'safe'),
			array('website','url')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'name' => $this->t('Business Name'),
			'website' => $this->t('Business Website'),
			'review' => $this->t('Please tell us why they should be featured'),
		);
	}
	
}