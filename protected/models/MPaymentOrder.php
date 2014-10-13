<?php

class MPaymentOrder extends UActiveRecord
{
	public static function module()
	{
		return 'payment';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{PaymentOrder}}';
	}

	public function rules()
	{
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, created, amount, status, method, custom', 'safe', 'on'=>'search'),
		);
	}
	
	public function behaviors()
	{
		return array(
			'TimestampBehavior' => array(
				'class' => 'UTimestampBehavior',
				'modified' => null,
			)
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'items' => array(self::HAS_MANY, 'MPaymentOrderItem', 'orderId'),
			'user' => array(self::BELONGS_TO, 'MUser', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => $this->t('Id'),
			'userId' => $this->t('User'),
			'created' => $this->t('Created'),
			'amount' => $this->t('Amount'),
			'status' => $this->t('Status'),
			'method' => $this->t('Method'),
			'custom' => $this->t('Custom'),
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

		$criteria->compare('id',$this->id,true);

		$criteria->compare('userId',$this->userId,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('amount',$this->amount);

		$criteria->compare('status',$this->status);

		$criteria->compare('method',$this->method,true);

		$criteria->compare('custom',$this->custom,true);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}