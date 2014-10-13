<?php
class MThemeColorScheme extends UActiveRecord
{
	public $colors;
	
	public static function module()
	{
		return 'customize';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{ThemeColorScheme}}';
	}

	public function rules()
	{
		return array(
			array('name', 'required', 'on' => 'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, themeId, name, current, value', 'safe', 'on'=>'search'),
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
			'themeId' => $this->t('Theme'),
			'name' => $this->t('Name'),
			'current' => $this->t('Current'),
			'value' => $this->t('Value'),
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

		$criteria->compare('themeId',$this->themeId);

		$criteria->compare('name',$this->name);

		$criteria->compare('current',$this->current);

		$criteria->compare('value',$this->value);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
	
	public function color($id)
	{
		if(!$this->colors)
			$this->colors = $this->value?unserialize($this->value):array();
		return isset($this->colors[$id])?$this->colors[$id]:null;
	}
}