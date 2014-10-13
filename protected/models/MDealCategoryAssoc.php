<?php
class MDealCategoryAssoc extends UActiveRecord
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
		return '{{DealCategoryAssoc}}';
	}

	public function rules()
	{
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, dealId, categoryId', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'deal' => array(self::BELONGS_TO, 'MDeal', 'dealId'),
                    'category' => array(self::BELONGS_TO, 'MDealCategory', 'categoryId'),
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
			'categoryId' => $this->t('Category'),
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

		$criteria->compare('dealId',$this->dealId,true);

		$criteria->compare('categoryId',$this->categoryId,true);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}