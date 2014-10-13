<?php
class MSubscriptionCampaignForm extends MSubscriptionCampaign
{
	public $scheduleField;
	public $listsField;
	
	public function rules()
	{
		return array(
			array('subject,plainBody,scheduleField','required'),
			array('listsField,htmlBody','safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'subject' => $this->t('Subject'),
			'plainBody' => $this->t('Plain Body'),
			'htmlBody' => $this->t('HTML Body'),
			'scheduleField' => $this->t('Schedule'),
			'listsField' => $this->t('Lists'),
		);
	}
	
	public function beforeValidate()
	{
		if(is_array($this->scheduleField))
			$this->scheduleField = UTimestamp::arrayToTimestamp($this->scheduleField);
		return parent::beforeValidate();
	}
	
	public function beforeSave()
	{
		if($this->scheduleField)
			$this->schedule = UTimestamp::applyGMT($this->scheduleField,param('timeZone'));
		return parent::beforeSave();
	}
	
	public function afterFind()
	{
		if($this->schedule)
			$this->scheduleField = UTimestamp::applyGMT($this->schedule,param('timeZone'),false);
		return parent::afterFind();
	}
}