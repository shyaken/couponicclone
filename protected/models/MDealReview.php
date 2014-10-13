<?php
class MDealReview extends UActiveRecord
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
		return '{{DealReview}}';
	}
	
	public function relations()
	{
		return array(
			'deal'=> array(self::BELONGS_TO, 'MDeal', 'dealId'),
		);
	}
	
	public function rules()
	{
		return array(
			array('dealId,name,website,review','safe'),
			array('id','safe','on'=>'search'),
		);
	}
	
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('dealId',$this->dealId);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('website',$this->website,true);

		$criteria->compare('review',$this->review,true);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}