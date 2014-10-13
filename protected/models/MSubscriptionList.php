<?php
class MSubscriptionList extends UActiveRecord
{
	public static function module()
	{
		return 'subscription';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{SubscriptionList}}';
	}

	public function rules()
	{
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, relatedId, title', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'subs' => array(self::STAT, 'MSubscriptionListEmail', 'listId'),
			'emails' => array(self::MANY_MANY, 'MSubscriptionEmail', '{{SubscriptionListEmail}}(listId,emailId)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => $this->t('Id'),
			'type' => $this->t('Type'),
			'relatedId' => $this->t('Related'),
			'title' => $this->t('Title'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('type',$this->type);

		$criteria->compare('relatedId',$this->relatedId);

		$criteria->compare('title',$this->title,true);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}