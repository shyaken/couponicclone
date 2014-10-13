<?php
class MDealGeneralForm extends MDeal
{
	public $startField;
	public $endField;
	public $expireField;
	public $redeemStartField;
	
	public function rules()
	{
		return array(
			array('timeZone,url,startField,endField,purchaseMin', 'required'),
			array('companyId', 'required', 'on' => 'admin'),
			array('commission, statsAdjust', 'safe', 'on' => 'admin'),
			array('priority,endField,redeemStartField,expireField,purchaseMax,limitPerUser,useCredits','safe'),
			array('url', 'unique', 'className' => 'MDeal', 'message' => $this->t('This url is already in use.')),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'companyId' => $this->t('Company'),
			'timeZone' => $this->t('Time Zone'),
			'url' => $this->t('Deal URL'),
			'startField' => $this->t('Start Date & Time'),
			'endField' => $this->t('End Date & Time'),
			'redeemStartField' => $this->t('Redeemable After'),
			'expireField' => $this->t('Coupons Expire'),
			'purchaseMin' => $this->t('Minimum Buyers Limit'),
			'purchaseMax' => $this->t('Maximum Buyers Limit'),
			'limitPerUser' => $this->t('Coupons Per User Limit'),
			'priority' => $this->t('Priority'),
			'commission' => $this->t('Commission'),
			'useCredits' => $this->t('Pay using Credits'),
			'statsAdjust' => $this->t('Adjust Sales Stats by'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeValidate()
	{
		if(is_array($this->startField))
			$this->startField = UTimestamp::arrayToTimestamp($this->startField);
		if(is_array($this->endField))
			$this->endField = UTimestamp::arrayToTimestamp($this->endField);
		if(is_array($this->expireField))
			$this->expireField = UTimestamp::arrayToTimestamp($this->expireField);
		if(is_array($this->redeemStartField))
			$this->redeemStartField = UTimestamp::arrayToTimestamp($this->redeemStartField);
		return parent::beforeValidate();
	}
	
	public function beforeSave()
	{
		if($this->startField)
			$this->start = UTimestamp::applyGMT($this->startField,$this->timeZone);
		if($this->endField)
			$this->end = UTimestamp::applyGMT($this->endField,$this->timeZone);
		if($this->expireField)
			$this->expire = UTimestamp::applyGMT($this->expireField,$this->timeZone);
		if($this->redeemStartField)
			$this->redeemStart = UTimestamp::applyGMT($this->redeemStartField,$this->timeZone);
			
		if(!$this->purchaseMax)
			$this->purchaseMax = null;
		if(!$this->limitPerUser)
			$this->limitPerUser = null;
		return parent::beforeSave();
	}
	
	public function afterFind()
	{
		if($this->start)
			$this->startField = UTimestamp::applyGMT($this->start,$this->timeZone,false);
		if($this->end)
			$this->endField = UTimestamp::applyGMT($this->end,$this->timeZone,false);
		if($this->expire)
			$this->expireField = UTimestamp::applyGMT($this->expire,$this->timeZone,false);
		if($this->redeemStart)
			$this->redeemStartField = UTimestamp::applyGMT($this->redeemStart,$this->timeZone,false);
		return parent::afterFind();
	}
}