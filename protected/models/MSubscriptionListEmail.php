<?php
class MSubscriptionListEmail extends UActiveRecord
{
	public $emailSearch;
	
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
		return '{{SubscriptionListEmail}}';
	}

	public function rules()
	{
		return array(
			array('listId','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, listId, emailSearch', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'email' => array(self::BELONGS_TO, 'MSubscriptionEmail', 'emailId'),
			'list' => array(self::BELONGS_TO, 'MSubscriptionList', 'listId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => $this->t('Id'),
			'listId' => $this->t('List'),
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

		$criteria->compare('t.id',$this->id);
		$criteria->with = array('email');
		$criteria->compare('listId',$this->listId);
		$criteria->compare('email.email',$this->emailSearch,true);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => 't.`id` DESC',
			),
		));
	}
}