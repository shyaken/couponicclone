<?php
class MDealForm extends MDeal
{
	public function rules()
	{
		return array(
			array('companyId,active,timeZone,url,start,end,purchaseMin', 'required'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function attributeLabels()
	{
		return array(
			'companyId' => $this->t('Company'),
			'active' => $this->t('Status'),
			'timeZone' => $this->t('Time Zone'),
			'url' => $this->t('Deal URL'),
			'start' => $this->t('Start Date & Time'),
			'end' => $this->t('End Date & Time'),
			'purchaseMin' => $this->t('Minimum Buyers Limit'),
		);
	}
}