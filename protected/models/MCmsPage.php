<?php
class MCmsPage extends UActiveRecord
{
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
		return '{{CmsPage}}';
	}

	public function rules()
	{
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, url, title, content, mainMenu, footerMenu', 'safe', 'on'=>'search'),
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
			'url' => $this->t('URL'),
			'title' => $this->t('Title'),
			'content' => $this->t('Content'),
			'mainMenu' => $this->t('Main Menu'),
			'footerMenu' => $this->t('Footer Menu'),
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

		$criteria->compare('url',$this->url,true);

		$criteria->compare('title',$this->title,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('mainMenu',$this->mainMenu);

		$criteria->compare('footerMenu',$this->footerMenu);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}