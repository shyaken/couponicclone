<?php
class WSubscriptionHelper extends USystemWorklet
{
	public function taskSubscriber($email)
	{
		$m = MSubscriptionEmail::model()->find('email=?', array($email));
		if(!$m)
		{
			$m = new MSubscriptionEmail;
			$m->email = $email;
			$m->hash = UHelper::hash('MSubscriptionEmail');
			$m->save();
		}
		return $m;
	}
	
	public function taskAddList($type,$relatedId,$title=null)
	{
		$m = new MSubscriptionList;
		$m->title = $title?$title:$this->listTitle($type,$relatedId);
		$m->type = $type;
		$m->relatedId = $relatedId;
		$m->save();
		return $m;
	}
	
	public function taskGetList($type,$relatedId,$autoAdd=false)
	{
		if($type == 100)
			$m = $this->getOverallList();
		else			
			$m = MSubscriptionList::model()->find('type=? AND relatedId=?',array(
				$type,$relatedId
			));
			
		if(!$m && $autoAdd)
			$m = $this->addList($type,$relatedId);
			
		return $m;
	}
	
	public function taskRemoveList($id)
	{
		MSubscriptionList::model()->deleteAll('id=?',array($id));
		MSubscriptionListEmail::model()->deleteAll('listId=?',array($id));
		MSubscriptionCampaignList::model()->deleteAll('listId=?',array($id));
	}
	
	public function taskAddEmailToList($email,$list,$hash=null)
	{
		if(is_array($list))
		{
			$m = $this->getList($list['type'],$list['relatedId']);
			if(!$m)
				$m = $this->addList($list['type'],$list['relatedId']);
			$list = $m;
		}
		else
			$list = $this->list($list);
		
		$sub = $this->subscriber($email);
		if($this->validList($list->id) && !$this->isSubscribed($sub,$list->id))
		{
			$m = new MSubscriptionListEmail;
			$m->listId = $list->id;
			$m->emailId = $sub->id;
			$m->save();
			
			if($list->type !== 100)
				$this->addEmailToList($email,$this->getOverallList()->id,$hash);
			
			return true;
		}
		return false;
	}
	
	public function taskRemoveEmailByHash($hash)
	{
		$sub = MSubscriptionEmail::model()->find('hash=?', array($hash));
		if($sub)
		{
			MSubscriptionListEmail::model()->deleteAll('emailId=?',array($sub->id));
			$sub->delete();
		}
	}
	
	public function taskRemoveEmailFromList($email,$listId)
	{
		$sub = $this->subscriber($email);
		if($listId == 100)
			MSubscriptionListEmail::model()->deleteAll('emailId=?',array($sub->id));
		else
			MSubscriptionListEmail::model()->deleteAll('listId=? AND emailId=?',array($listId,$sub->id));
	}
	
	public function taskAddCampaign($data)
	{
		extract($data);
		
		$m = isset($id) && $id ? MSubscriptionCampaign::model()->findByPk($id) : new MSubscriptionCampaign;
		$m->subject = $subject;
		$m->htmlBody = $htmlBody;
		$m->plainBody = $plainBody;
		$m->schedule = $schedule;
		$m->save();
		
		MSubscriptionCampaignList::model()->deleteAll('campaignId=?',array($m->id));
		foreach($lists as $id)
		{
			$l = new MSubscriptionCampaignList;
			$l->campaignId = $m->id;
			$l->listId = $id;
			$l->save();
		}
		
		return $m->id;
	}
	
	public function taskRemoveCampaign($id)
	{
		MSubscriptionCampaign::model()->deleteAll('id=?',array($id));
		MSubscriptionCampaignList::model()->deleteAll('campaignId=?',array($id));
	}
	
	public function taskGetListEmails($lists,$offset=0,$limit=0)
	{
		$c = new CDbCriteria();
		foreach($lists as $type => $ids)
		{			
			$join = rtrim(implode(',',$ids),',');
			if($join)
				$c->addCondition('emailId in (SELECT emailId FROM {{SubscriptionListEmail}} WHERE listId in ('.$join.'))');
		}
		$c->group = 'emailId';
		$c->order = 'id ASC';
		if($offset)
			$c->offset = $offset;
		if($limit)
			$c->limit = $limit;
		return MSubscriptionListEmail::model()->findAll($c);		
	}
	
	public function taskValidList($listId)
	{
		return MSubscriptionList::model()->exists('id=?',array($listId));
	}
	
	public function taskIsSubscribed($email,$listId)
	{
		return MSubscriptionListEmail::model()->exists('listId=? AND emailId=?',array($listId,$email->id));
	}
	
	public function taskList($id)
	{
		return MSubscriptionList::model()->findByPk($id);
	}
	
	public function taskListTitle($type,$relatedId)
	{
		switch($type)
		{
			case '0':
				$title = 'City: {name}';
				$name = wm()->get('location.helper')->locationAsText(MLocation::model()->findByPk($relatedId), false, false, ' ');
				break;
			case '1':
				$title = 'Deal: {name}';
				$name = MDeal::model()->findByPk($relatedId)->name;
				break;
			case '2':
				$title = 'Category: {name}';
				$name = $relatedId ? wm()->get('deal.category.helper')->category($relatedId)->name : $this->t('All');
				break;
		}
		return $this->t($title, array('{name}' => $name));
	}
	
	public function taskGetOverallList()
	{
		$m = MSubscriptionList::model()->find('type=100');
		if(!$m)
		{
			$m = new MSubscriptionList;
			$m->title = $this->t('All Subscribers');
			$m->type = 100;
			$m->save();
		}
		return $m;
	}
}