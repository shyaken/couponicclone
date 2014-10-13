<?php

class MPaymentOrderItem extends UActiveRecord
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
		return '{{PaymentOrderItem}}';
	}

	public function rules()
	{
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, orderId, itemModule, itemId, quantity', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'order' => array(self::BELONGS_TO, 'MPaymentOrder', 'orderId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => $this->t('Id'),
			'orderId' => $this->t('Order'),
			'itemModule' => $this->t('Item Module'),
			'itemId' => $this->t('Item'),
			'quantity' => $this->t('Quantity'),
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

		$criteria->compare('orderId',$this->orderId,true);

		$criteria->compare('itemModule',$this->itemModule,true);

		$criteria->compare('itemId',$this->itemId,true);

		$criteria->compare('quantity',$this->quantity,true);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}