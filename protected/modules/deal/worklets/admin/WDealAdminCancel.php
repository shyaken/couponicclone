<?php
class WDealAdminCancel extends UConfirmWorklet
{
	public function title()
	{
		return $this->t('Do you really want to cancel {deal}?',array(
			'{deal}' => $this->deal()->name
		));
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskDescription()
	{
		return $this->t('When you cancel a deal all authorized payments will be voided and all charged ones will be refunded.');
	}
	
	public function taskDeal()
	{
		static $deal;		
		if(!isset($deal))
			$deal = isset($_GET['id'])?MDeal::model()->findByPk($_GET['id']):null;
		return $deal;
	}
	
	public function taskYes()
	{
		if($this->deal()->status != 2)
		{
			wm()->get('deal.order')->cancelDeal($_GET['id']);
			$this->deal()->status = 2;
			$this->deal()->save();
			// remove email campaign
			$campaign = MDealSubscriptionCampaign::model()->findByPk($_GET['id']);
			if($campaign)
			{
				wm()->get('subscription.helper')->removeCampaign($campaign->campaignId);
				$campaign->delete();
			}
		}
	}
}