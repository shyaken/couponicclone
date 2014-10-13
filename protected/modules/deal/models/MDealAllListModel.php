<?php
class MDealAllListModel extends MDeal
{
	public $location;
	public $category=array();
	public $type='active';
	
	public function search()
	{
		$c=new CDbCriteria;
		$c->with = array('imageBin','stats','categories');
		$order = '`priority` ASC';
		
		$gmtNow = UTimestamp::getNow();
		
		if($this->type == 'upcoming')
		{
			$c->compare('t.active','1');
			$c->compare('t.status','1');
			$c->compare('start','>'.$gmtNow);
			$order = '`start` ASC';
		}
		else
		{
			$c->compare('t.active','1');
			$c->compare('t.status','1');
			$c->compare('start','<='.$gmtNow);
			$c->compare('end','>='.$gmtNow);
		}
		
		if($this->getModule()->param('categories') >= 0 && count($this->category))
		{
			$c->addInCondition('categories.id', $this->category);
		}
		
		if($this->getModule()->param('categories') <= 0)
		{
			$c->with[] = 'locs';
			$c->addCondition('locs.location = 0 OR locs.location = '.$this->location);
		}
		
		$c->group = 't.id';
		
		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$c,
			'sort' => array(
				'defaultOrder' => $order,
			),
		));
	}
}