<?php
class MDealSeoForm extends MDeal
{
	public $metaKeywords;
	public $metaDescription;
	
	public function rules()
	{
		return array(
			array('metaKeywords,metaDescription', 'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'metaKeywords' => $this->t('Meta Keywords'),
			'metaDescription' => $this->t('Meta Description'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeValidate()
	{
		$fields = array('metaKeywords','metaDescription');
		foreach($fields as $f)
		if(is_array($this->$f))
			foreach($this->$f as $k=>$v)
				if(!$v)
					unset($this->$f[$k]);
		return parent::beforeValidate();
	}
}