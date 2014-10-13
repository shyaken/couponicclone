<?php
class MLocationPresetForm extends MLocationPreset
{
	public $cityName;
	
	public function rules()
	{
		return array(
			array('url', 'required'),
			array('url', 'unique', 'className' => 'MLocationPreset', 'message' => $this->t('This URL is already taken.')),
			array('lon,lat,cityName', 'safe'),
			array('background', 'safe', 'on' => 'update'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'cityName' => $this->t('City Name Translations'),
			'url' => $this->t('Shortcut URL'),
			'lon' => $this->t('Longitude'),
			'lat' => $this->t('Latitude'),
			'background' => $this->t('City Background Image'),
		);
	}
	
	public function relations()
	{
		return array(
			'i18n' => array(self::HAS_MANY, 'MI18N', 'relatedId', 'on' => "model='Location'"),
		);
	}
	
	public function beforeValidate()
	{
		if(is_array($this->cityName))
			foreach($this->cityName as $k=>$v)
				if(!$v)
					unset($this->cityName[$k]);
		return parent::beforeValidate();
	}
}