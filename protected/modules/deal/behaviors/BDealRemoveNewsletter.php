<?php
class BDealRemoveNewsletter extends UWorkletBehavior
{
	public function afterDelete($id)
	{
		MDealSubscriptionCampaign::model()->deleteAll('campaignId=?',array($id));
	}
}