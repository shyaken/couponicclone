<?php
class MDealMediaForm extends MDealMedia
{
	public $image;
	public $embed;
	
	public function rules()
	{
		return array(
			array('dealId,type,embed','required'),
			array('image','safe'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeSave()
	{
		if($this->type == 2)
			$this->data = $this->embed;
		return parent::beforeSave();
	}
	
	public function attributeLabels()
	{
		return array(
			'type' => $this->t('Media Type'),
			'image' => $this->t('Upload Image'),
			'embed' => $this->t('Input Embed Code'),
		);
	}
}