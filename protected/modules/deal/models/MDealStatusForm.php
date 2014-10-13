<?php
class MDealStatusForm extends MDeal
{
	public $active;
	
	public function rules()
	{
		return array(
			array('active', 'required'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'active' => $this->t('Status'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}