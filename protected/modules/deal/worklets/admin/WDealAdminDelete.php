<?php
class WDealAdminDelete extends UDeleteWorklet
{
	public $modelClassName = array(
		'MDeal'=>'id',
		'MDealStats'=>'id',
		'MDealCache'=>'dealId',
		'MDealCoupon'=>'dealId',
		'MDealPrice'=>'dealId',
	);
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeDelete($id)
	{
		$allow = app()->user->checkAccess('administrator') || (
			$this->company($id)
			&& app()->user->checkAccess('company.edit',$this->company($id),false)
			&& $this->deal($id) && $this->deal($id)->active == 0);
		
		if(!$allow)
		{
			$this->accessDenied();
			return false;
		}
		
		$orders = MPaymentOrderItem::model()->count('itemModule=:itemModule
			AND itemId IN (SELECT id FROM {{DealPrice}} 
			WHERE dealId=:dealId)', array(':itemModule' => 'deal',':dealId' => $id));
		if($orders)
			throw new CHttpException(403, $this->t('There is already an order associated with this deal. It can\'t be removed.'));
	}
	
	public function taskCompany($id)
	{
		static $companies=array();
		
		if(!isset($companies[$id]))
			$companies[$id] = $this->deal($id)->company;
		
		return $companies[$id];
	}
	
	public function taskDeal($id)
	{
		static $deals=array();
		
		if(!isset($deals[$id]))
			$deals[$id] = MDeal::model()->findByPk($id);
		
		return $deals[$id];
	}
	
	public function taskDelete($id)
	{
		$ms = MPaymentOrder::model()->with('items')->findAll('items.itemId=? AND items.itemModule=?',
			array($id,'deal'));
		foreach($ms as $m)
		{
			MPaymentOrderItem::model()->deleteAll('orderId=?',array($m->id));
			$m->delete();
		}
		$ms = MDealMedia::model()->findAll('dealId=?',array($id));
		foreach($ms as $m)
			wm()->get('deal.media.delete')->delete($m->id);
			
		// remove email campaign
		$campaign = MDealSubscriptionCampaign::model()->findByPk($id);
		if($campaign)
		{
			wm()->get('subscription.helper')->removeCampaign($campaign->campaignId);
			$campaign->delete();
		}
		
		MI18N::model()->deleteAll('model=? AND relatedId=?', array('Deal', $id));
			
		parent::taskDelete($id);
	}
	
	public function taskDeleteUser($id)
	{
		MDealCoupon::model()->deleteAll('userId=?',array($id));
	}
}