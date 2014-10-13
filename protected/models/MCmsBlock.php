<?php
class MCmsBlock extends UActiveRecord
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
		return '{{CmsBlock}}';
	}

	public function rules()
	{
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, space, position, title, show, hide, content', 'safe', 'on'=>'search'),
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
			'space' => $this->t('Space'),
			'position' => $this->t('Position'),
			'title' => $this->t('Title'),
			'show' => $this->t('Show'),
			'hide' => $this->t('Hide'),
			'content' => $this->t('Content'),
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

		$criteria->compare('space',$this->space,true);

		$criteria->compare('position',$this->position,true);

		$criteria->compare('title',$this->title,true);

		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getPositionData()
	{
		$tmp = explode(':',$this->position);
		return count($tmp)>1 ? array($tmp[0] => $tmp[1]) : $this->position;
	}
}