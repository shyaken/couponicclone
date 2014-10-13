<?php
class MCompany extends UActiveRecord
{	
	public $city;
	
	public static function module()
	{
		return 'company';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{Company}}';
	}

	public function relations()
	{
		return array(
			'loc' => array(self::BELONGS_TO, 'MLocation', 'location'),
			'user' => array(self::BELONGS_TO, 'MUser', 'userId'),
		);
	}
	
	public function rules()
	{
		return array(
			array('id,name,website,phone,country','safe','on'=>'search'),
		);
	}
	
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		
		$criteria->with = 'loc';

		$criteria->compare('name',$this->name,true);

		$criteria->compare('website',$this->website,true);
		
		$criteria->compare('phone',$this->phone,true);
		
		$criteria->compare('loc.city',$this->city,true);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}