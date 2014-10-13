<?php
class MBaseLanguageForm extends UFormModel
{
	public $code;
	public $name;
	
	public static function module()
	{
		return 'base';
	}
	
	public function rules()
	{
		return array(
			array('code,name','required'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'code' => $this->t('Language Code'),
			'name' => $this->t('Language Name'),
		);
	}
}