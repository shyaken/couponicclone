<?php
class MSubscriptionCampaign extends UActiveRecord
{
	public $status;
	
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
		return '{{SubscriptionCampaign}}';
	}

	public function rules()
	{
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, status, subject, htmlBody, plainBody, schedule, complete', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'lists' => array(self::MANY_MANY, 'MSubscriptionList', '{{SubscriptionCampaignList}}(campaignId,listId)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => $this->t('Id'),
			'subject' => $this->t('Subject'),
			'htmlBody' => $this->t('Html Body'),
			'plainBody' => $this->t('Plain Body'),
			'schedule' => $this->t('Schedule'),
			'complete' => $this->t('Complete'),
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

		$criteria->compare('subject',$this->subject,true);
		
		if($this->complete)
			$criteria->compare('complete',$this->complete);
		
		if($this->schedule)
			$criteria->compare('schedule','>='.utime(UTimestamp::getGMT(strtotime($this->schedule))));
		
		switch($this->status)
		{
			case '1':
				$criteria->compare('complete','>=0');
				$criteria->compare('schedule','>'.time());
				break;
			case '2':
				$criteria->compare('complete','>=0');
				$criteria->compare('schedule','<='.time());
				break;
			case '3':
				$criteria->compare('complete','<0');
				break;
				
		}

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => 'schedule DESC',
			),
		));
	}
}