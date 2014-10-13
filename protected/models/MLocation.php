<?php
class MLocation extends UActiveRecord
{
	public static function module()
	{
		return 'location';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{Location}}';
	}
	
	public function relations()
	{
		return array(
			'i18n' => array(self::HAS_MANY, 'MI18N', 'relatedId', 'on' => "model='Location'"),
			'preset' => array(self::HAS_ONE, 'MLocationPreset', 'location'),
		);
	}

	public function getCityName()
	{
		$city = $this->translate('cityName',null,true);
		return $city ? $city : $this->city;
	}
	
	public function rules()
	{
		return array(
			array('country,state,city,cityASCII','safe')
		);
	}
}