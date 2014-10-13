<?php
class MDealReviewForm extends MDealReview
{	
	public function rules()
	{
		return array(
			array('name, website, review', 'required'),
			array('website', 'url'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'name' => $this->t('Name'),
			'website' => $this->t('Website'),
			'review' => $this->t('Review'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeSave()
	{
		$purifier = new CHtmlPurifier;
		$this->review = $purifier->purify($this->review);
		return true;
	}
}