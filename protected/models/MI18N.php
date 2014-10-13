<?php
class MI18N extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{I18N}}';
	}
	
	public function rules()
    {
        return array(
			array('model, relatedId, language, name, value', 'safe')
        );
    }
}