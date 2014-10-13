<?php
class MDealInfoForm extends MDeal
{
	public $finePrint;
	public $highlights;
	public $description;
	
	public function rules()
	{
		return array(
			array('finePrint,highlights,description', 'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'finePrint' => $this->t('Fine Print'),
			'highlights' => $this->t('Highlights'),
			'description' => $this->t('Description'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeValidate()
	{
		$fields = array('finePrint','highlights','description');
		foreach($fields as $f)
		if(is_array($this->$f))
			foreach($this->$f as $k=>$v)
				if(!$v)
					unset($this->$f[$k]);
		return parent::beforeValidate();
	}
}