<?php
class MCitymanagerLevelForm extends MCitymanager
{
	public $allLocations;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return array(
			array('level','required'),
			array('allLocations', 'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'level' => $this->t('Access Level'),
			'allLocations' => $this->t('Location'),
		);
	}
}