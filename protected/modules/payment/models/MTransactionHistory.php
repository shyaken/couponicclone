<?php
class MTransactionHistory extends UActiveRecord
{
    
        public $action;
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
		return '{{TransactionHistory}}';
	}

	public function rules()
	{
		return array(
			array('id, userId, amount, comment, date, action', 'safe'),
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
			'userId' => $this->t('User'),
			'amount' => $this->t('Amount'),
			'comment' => $this->t('Comment'),
			'date' => $this->t('Date'),
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

		$criteria->compare('userId',$this->userId);

		$criteria->compare('amount',$this->amount);

		$criteria->compare('comment',$this->comment,true);

                if($this->date)
                    $criteria->compare('date','>'.strtotime($this->date));

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}