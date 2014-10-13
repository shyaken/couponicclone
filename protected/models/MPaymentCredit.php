<?php

class MPaymentCredit extends UActiveRecord
{
	public static function module()
	{
		return 'base';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{PaymentCredit}}';
	}

	public function rules()
	{
		return array(
			array('amount','required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, amount', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => $this->t('Id'),
			'amount' => $this->t('Amount'),
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

		$criteria->compare('amount',$this->amount);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}