<?php
class MDealBackgroundForm extends MDeal
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return array(
			array('background', 'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'background' => $this->t('Custom Background Image'),
		);
	}
}