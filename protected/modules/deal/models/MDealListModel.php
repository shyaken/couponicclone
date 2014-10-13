<?php
class MDealListModel extends MDeal
{
	public $views;
	public $bought;
	public $city;
	public $nameSearch;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return CMap::mergeArray(parent::rules(), array(
			array('nameSearch,city','safe','on' => 'search'),
		));
	}
	
	public function relations()
	{
		return CMap::mergeArray(parent::relations(),array(
			'namei18n' => array(
				self::HAS_ONE,
				'MI18N',
				'relatedId',
				//'joinType' => 'INNER JOIN',
				'on' => "namei18n.`name` = 'name' AND namei18n.`model` = 'Deal'",
				'together' => true,
			),
			'cities' => array(
				self::MANY_MANY,
				'MLocation',
				'{{DealLocation}}(dealId,location)',
				'joinType' => 'INNER JOIN',
				'together' => true,
			),
		));
	}
	
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;		
		
		$criteria->with = array('company','stats','cache','namei18n');
		
		$criteria->compare('t.id',$this->id);

		$criteria->compare('url',$this->url,true);

		$criteria->compare('companyId',$this->companyId);

		if($this->nameSearch)
			$criteria->compare('namei18n.value',$this->nameSearch,true);
		
		if($this->start)
			$criteria->compare('start','>='.utime(UTimestamp::getGMT(strtotime($this->start))));

		if($this->end)
			$criteria->compare('end','<='.utime(UTimestamp::getGMT(strtotime($this->end))));
			
		if($this->city == '*')
		{
			$criteria->with[] = 'locs';
			$criteria->compare('locs.location',0);
		}
		elseif($this->city)
		{
			$criteria->with[] = 'cities';
			$criteria->compare('cities.city',$this->city,true);
		}

		$criteria->compare('expire',$this->expire);

		$criteria->compare('purchaseMin',$this->purchaseMin);

		$criteria->compare('purchaseMax',$this->purchaseMax);

		$criteria->compare('limitPerUser',$this->limitPerUser);

		$criteria->compare('finePrint',$this->finePrint,true);

		$criteria->compare('highlights',$this->highlights,true);

		$criteria->compare('image',$this->image);
		
		if($this->status)
		{
			$statusCriteria = new CDbCriteria;
			switch($this->status)
			{
				case 'draft':
					$criteria->compare('active',0);
					break;
				case 'awaiting':
					$criteria->compare('active',2);
					break;
				case 'active_':
					$criteria->compare('active',1);
					break;
				case 'cancelled':
					$criteria->compare('status',2);
					break;
				case 'paid':
					$criteria->compare('status',3);
					break;
				case 'active':
					$statusCriteria->condition = 'active=:active AND status=:status AND (stats.bought is null
						OR stats.bought < t.purchaseMin) AND t.end > UNIX_TIMESTAMP()';
					$statusCriteria->params = array(
						':active'=>1,':status'=>1);
					break;
				case 'tipped':
                                        
					$statusCriteria->condition = 'active=:active AND status=:status
						AND stats.bought >= t.purchaseMin
						AND (stats.bought <= t.purchaseMax OR t.purchaseMax is NULL)
						AND t.end > UNIX_TIMESTAMP()';
					$statusCriteria->params = array(
						':active' => 1, ':status' => 1,
					);
					break;
				case 'closed':
					$statusCriteria->condition = 'active=:active AND status=:status
						AND (stats.bought >= t.purchaseMax OR t.end < UNIX_TIMESTAMP())';
					$statusCriteria->params = array(
						':active' => 1, ':status' => 1,
					);
					break;
			}
			$criteria->mergeWith($statusCriteria);		
		}
		
		$criteria->group = 't.id';
		
		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria, 
			'sort' => array(
				'attributes' => array(
					'id'=>'t.id',
					'nameSearch'=>'namei18n.value',
					'start', 'end',
					'companyId'=>'company.name',
					'views'=>array(
						'asc' => 'stats.views',
						'desc' => 'stats.views DESC',
						'label' => $this->t('Views'),
					),
					'bought'=>array(
						'asc' => 'stats.bought',
						'desc' => 'stats.bought DESC',
						'label' => $this->t('Bought'),
					),
				),
			),
		));
		
	}
}