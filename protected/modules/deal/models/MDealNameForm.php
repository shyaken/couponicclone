<?php
class MDealNameForm extends MDeal
{
	public $name;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('companyId', 'required', 'on' => 'admin'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'companyId' => $this->t('Company'),
			'name' => $this->t('Name'),
		);
	}
	
	public function beforeValidate()
	{
		if(is_array($this->name))
			foreach($this->name as $k=>$v)
				if(!$v)
					unset($this->name[$k]);
		return parent::beforeValidate();
	}
}