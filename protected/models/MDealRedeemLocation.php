<?php
class MDealRedeemLocation extends UActiveRecord
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
		return '{{DealRedeemLocation}}';
	}

	public function rules()
	{
		return array(
			array('dealId,address','required'),
			array('zipCode,lon,lat','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, dealId, location, address, zipCode, lon, lat', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'loc' => array(self::BELONGS_TO,'MLocation','location'),
			'deal' => array(self::BELONGS_TO,'MDeal','dealId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => $this->t('Id'),
			'dealId' => $this->t('Deal'),
			'location' => $this->t('Location'),
			'address' => $this->t('Address'),
			'zipCode' => $this->t('Zip Code'),
			'lon' => $this->t('Longitude'),
			'lat' => $this->t('Latitude'),
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

		$criteria->compare('dealId',$this->dealId);

		$criteria->compare('location',$this->location);

		$criteria->compare('address',$this->address,true);

		$criteria->compare('zipCode',$this->zipCode);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}