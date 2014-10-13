<?php
class MLocationPreset extends UActiveRecord
{
	public static function module()
	{
		return 'location';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{LocationPreset}}';
	}

	public function rules()
	{
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('location, url, lon, lat', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'loc' => array(self::BELONGS_TO, 'MLocation', 'location'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'location' => $this->t('Location'),
			'url' => $this->t('Url'),
			'lon' => $this->t('Lon'),
			'lat' => $this->t('Lat'),
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

		$criteria->compare('location',$this->location,true);

		$criteria->compare('url',$this->url,true);

		$criteria->compare('lon',$this->lon);

		$criteria->compare('lat',$this->lat);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}