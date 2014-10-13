<?php
class WDealCron extends UCronWorklet
{
	public $emailLimit = 10;
	
	public function taskBuild()
	{
		$this->cancel();
		$this->charge();
		$this->newsletters();
        $this->subscriptionDelete();
	}
	
	public function taskCharge()
	{
		$models = MDeal::model()->findAllByAttributes(array('status'=>1, 'active'=>1));
		$success = 0;
		$fail = 0;
		foreach($models as $m)
		{
			$s = wm()->get('deal.helper')->dealStatus($m);
			if($s == 'tipped' || $s == 'closed')
			{
				$r = wm()->get('deal.order')->chargeDeal($m->id);
				$success+= $r[0];
				$fail+= $r[1];
			}
		}
		$this->addResult($this->t('Orders charged: {success}. Orders failed: {fail}.',
			array('{success}'=>$success, '{fail}' => $fail)));
	}
	
	public function taskCancel()
	{
		$url = wm()->get('deal.admin.update');
		$c = new CDbCriteria(array(
			'with' => array('stats'),
			'condition' => 'active=? AND status=? AND end<=? AND (stats.bought IS NULL OR stats.bought<t.purchaseMin)',
			'params' => array(1,1,UTimestamp::getNow()),
		));
		$models = MDeal::model()->findAll($c);
		foreach($models as $m)
		{
			wm()->get('deal.order')->cancelDeal($m->id);
			$m->url = $url->genUrl();
			$m->status = 2;
			$m->save();
		}
		$this->addResult($this->t('{num} deals have been cancelled.', array('{num}'=>count($models))));
	}
	
	public function taskNewsletters()
	{
		// finding deals with priority = 1 (featured) and no campaign already created for them
		$c = new CDbCriteria;
		$c->with = array('campaign');
		$c->compare('t.active','1');
		$c->compare('t.status','1');
		$c->compare('start','<='.(time()+3600));
		$c->compare('end','>'.time());
		$c->compare('t.priority','1');
		$c->addCondition('campaign.id IS NULL');
		
		$deals = MDeal::model()->findAll($c);
		foreach($deals as $deal)
		{
			$c = new CDbCriteria;			
			$c->compare('t.active','1');
			$c->compare('t.status','1');
			$c->compare('start','<='.(time()+3600));
			$c->compare('end','>'.time());
			$c->compare('t.priority','>1');
			
			if($this->param('categories') >= 0)
			{
				$categories = array();
				foreach($deal->categories as $category)
					$categories[] = $category->id;
					
				if(count($categories))
				{
					$c->with[] = 'categories';
					$c->addInCondition('categories.id', $categories);
				}
			}
			if($this->param('categories') <= 0)
			{
				$locs = array();
				foreach($deal->locs as $loc)
					$locs[] = $loc->location;
					
				if(count($locs))
				{
					$c->with[] = 'locs';
					$c->addCondition('locs.location = 0 OR locs.location IN( '.implode(',',$locs).' )');
				}
			}
			$side = MDeal::model()->findAll($c);
			
			wm()->get('deal.helper')->emailCampaign($deal, $side);
		}
	}
	
	public function subscriptionDelete()
	{
		if(!$this->param('subscriptionDelete'))
			return;
	
		$endTime = time() - $this->param('subscriptionDelete')*3600*24;
	
		$c = new CDbCriteria;
		$c->with = array('list');
		$c->condition = '(t.status > 1 OR t.end < '.$endTime.') AND list.id IS NOT NULL';
		
		$deals = MDeal::model()->findAll($c);
		foreach($deals as $deal)
			wm()->get('subscription.helper')->removeList($deal->list->id);
	}
}