<?php
class WSubscriptionCron extends UCronWorklet
{
	public function taskBuild()
	{
		$this->send();
	}
	
	public function taskSend()
	{
		$counter = 0;
		$models = MSubscriptionCampaign::model()->findAll('complete>=0 AND schedule <= :time',array(
			':time' => time()
		));
		foreach($models as $m)
		{
			$complete = 0;
			$lists = array();
			foreach($m->lists as $l)
				$lists[$l->type][] = $l->id;
				
			if(!count($lists))
			{
				$m->complete = -1;
				$m->save();
				continue;
			}
			
			if(m('deal')->param('categories')>=0 && isset($lists[2]))
				$lists[2][] = wm()->get('subscription.helper')->getList(2,0,true)->id;
			
			$emails = wm()->get('subscription.helper')->getListEmails($lists,
				$m->complete,$this->param('emailsLimit'));
				
			if(!count($emails))
			{
				$m->complete = -($m->complete+1);
				$m->save();
				continue;
			}
			
			foreach($emails as $e)
			{
				app()->mailer->send(array(
					'to' => $e->email->email,
					'from' => array(
						'name' => app()->name,
						'email' => app()->param('newsletterEmail'),
					),
				),null,array(
					'subject' => $m->subject,
					'htmlBody' => $m->htmlBody,
					'plainBody' => $m->plainBody,
					'subscription' => $e->email->hash,
				));
				$counter++;
				$complete++;
			}
			$m->complete+= $complete;
			$m->save();
		}
		$this->addResult($this->t('{num} newsletters have been sent.', array('{num}'=>$counter)));
	}
}