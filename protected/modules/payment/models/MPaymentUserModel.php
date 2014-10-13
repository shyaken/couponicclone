<?php
class MPaymentUserModel extends MUser
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('email',$this->email,true);
		$criteria->with = 'credit';
		
		$nameCriteria = new CDbCriteria;
		$nameCriteria->compare('firstName',$this->lastName,true);
		$nameCriteria->compare('lastName',$this->lastName,true,'OR');
		
		$criteria->mergeWith($nameCriteria);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getName()
	{
		return $this->lastName
			? $this->lastName.', '.$this->firstName
			: $this->firstName;
	}
}