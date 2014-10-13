<?php
class MUserProfileSetting extends UActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function module()
    {
        return 'user';
    }
	
	public function tableName()
	{
		return '{{UserProfileSetting}}';
	}
	
    public function rules()
    {
		return array(
			array('label, type, rules','required'),
			array('items','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('label, type', 'safe', 'on'=>'search'),
		);
	}
	
    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('label',$this->label,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
    }
	
	public function getItems()
	{
		 $str = $this->itemlist;
		 $items = explode("\n", $str);
		 return $items;
	}
}