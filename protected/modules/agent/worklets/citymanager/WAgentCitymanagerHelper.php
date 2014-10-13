<?php
class WAgentCitymanagerHelper extends USystemWorklet
{
	public function taskInfo($key)
	{
		$info = array(
			'title' => $this->t('City Manager'),
			'title_s' => $this->t('City Managers'),
			'description' => $this->t('City Manager is a sub-admin (moderator) who can manage deals and companies only within specified by admin city(ies).'),
		);
		return isset($info[$key])?$info[$key]:null;
	}
	
	public function taskApplyCriteria($criteria, $type, $user=null)
	{
		$user = $user?$user:app()->user->model();
		$locations = array();
		$ids = array();
		
		$levels = MCitymanager::model()->findAll('userId=? AND level=1',array($user->id));
		foreach($levels as $l)
		{
			if($l->location == 0)
				return $criteria;
			$locations[] = $l->location;
		}
		
		$items = MCitymanagerItem::model()->findAll('userId=? AND itemType=?', array($user->id,$type));
		foreach($items as $i)
			$ids[] = $i->itemId;
			
		if(!count($ids) && !count($locations))
			$criteria->addCondition('0=1', 'AND');
		else
		{			
			$newCriteria = new CDbCriteria;
			switch($type)
			{
				case 'company':
					if(count($ids))
						$newCriteria->addInCondition('t.id',$ids,'OR');
					if(count($locations))
						$newCriteria->addInCondition('t.location',$locations,'OR');
					break;
				case 'deal':
					if(count($ids))
						$newCriteria->addInCondition('t.id',$ids,'OR');
					if(count($locations))
						$newCriteria->addInCondition('locs.location',$locations,'OR');
					break;
			}
			$criteria->mergeWith($newCriteria);
		}
		return $criteria;
	}
	
	public function taskCheckAccess($id, $locations, $type, $user=null)
	{
		$user = $user?$user:app()->user->model();
		// first we will check if manager has direct access to the item
		$item = MCitymanagerItem::model()->find('itemId=? AND itemType=? AND userId=?', array(
			$id, $type, $user->id
		));
		if($item)
			return true;
			
		// if there are no locations to check - grant access - likely to be a new deal that agent is trying to create
		if(!count($locations))
			return true;
		
		$c = new CDbCriteria;	
		$c->compare('userId', $user->id);
		$c->compare('level', 1);
		// if this is a deal for all locations - only grant access if agent has access to all locations also
		if($locations[0] == '0')
			$c->compare('location', '0');			
		// or check against all locations		
			$c->addInCondition('location', $locations);
			
		$level = MCitymanager::model()->find($c);
		if($level)
			return true;
			
		return false;
	}
	
	public function taskGrantAccess($id, $type, $user=null)
	{
		$user = $user?$user:app()->user->model();
		$exists = MCitymanagerItem::model()->exists('userId=? AND itemType=? AND itemId=?', array(
			$user->id, $type, $id
		));
		if(!$exists)
		{
			$m = new MCitymanagerItem;
			$m->userId = $user->id;
			$m->itemType = $type;
			$m->itemId = $id;
			$m->save();
		}
	}
	
	public function taskLocations($user=null)
	{
		$locations = array();
		$user = $user?$user:app()->user->model();
		$models = MCitymanager::model()->findAll('userId=?', array($user->id));
		foreach($models as $m)
			if($m->location == 0)
				return true;
			else
				$locations[] = $m->location;
		return $locations;
	}
}