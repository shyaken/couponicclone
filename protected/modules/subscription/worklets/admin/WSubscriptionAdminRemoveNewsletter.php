<?php
class WSubscriptionAdminRemoveNewsletter extends UDeleteWorklet
{
	public $modelClassName = 'MSubscriptionCampaign';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskDelete($id)
	{
		wm()->get('subscription.helper')->removeCampaign($id);
	}
}