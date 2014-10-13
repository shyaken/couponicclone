<?php
class MUserProfile extends UActiveRecord
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
		return '{{UserProfile}}';
	}
	
    public function rules()
    {
        return array(
			 array('value, userId, settingId, id', 'safe'),
        );
    }
	
    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('value',$this->value,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('settingId',$this->settingId,true);		

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
    }

}