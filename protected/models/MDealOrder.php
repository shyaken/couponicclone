<?php
class MDealOrder extends UActiveRecord
{	
	public static function module()
	{
		return 'deal';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{DealOrder}}';
	}

	public function rules()
	{
	}
}