<?php
class MDealMediaDescriptionForm extends MDealMedia
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return array(
			array('description', 'safe'),
		);
	}
}