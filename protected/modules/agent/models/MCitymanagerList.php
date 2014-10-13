<?php
class MCitymanagerList extends MUser
{
	public $city;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function relations()
	{
		return array_merge(parent::relations(),array(
			'locs' => array(self::HAS_MANY, 'MCitymanager', 'userId')
		));
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$criteria->with = array('locs');

		$criteria->compare('id',$this->id);

		$criteria->compare('email',$this->email,true);
		
		$criteria->compare('role','citymanager',true);
			
		$criteria->compare('firstName',$this->lastName,true);
		
		$criteria->compare('lastName',$this->lastName,true);
		
		$criteria->compare('locs.city',$this->city,true);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}