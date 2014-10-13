<?php
class WDealInstallInstall extends UInstallWorklet
{	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'fileTypes' => 'jpg, gif, png',
	        'fileSizeLimit' => '4',
	        'fileResize' => '440x440',
	        'commission' => '10',
			'rssChannelDescription' => 'Get enough people to win a massive discount on something fun to do in {city}',
			'delimiter' => ',',
			'requireSubscribe' => '0',
			'categories' => '-1',
			'upcoming' => '1',
			'homepage' => 'deal.view',
			'subscriptionDelete' => '0',
			'payoutMode' => 'total',
		));
	}
	
	public function taskModuleFilters()
	{
		return array(
			'admin' => 'deal.main',
			'base' => 'deal.main',
			'deal' => 'deal.main',
			'payment' => 'deal.main',
			'user' => 'deal.main',
			'subscription' => 'deal.main',
		);
	}
	
	public function taskModuleAuth()
	{
		return array(
			'items' => array(
				'coupon.access' => array(0,'coupon print/mark as used access',NULL,NULL),
				'user.coupon.access' => array(1,NULL,'return $params->userId===app()->user->id && $params->order->status == 2;',NULL),
				'company.coupon.access' => array(1,NULL,'return $params->deal->company->userId===app()->user->id;',NULL),
			),
			'children' => array(
				'user' => array('user.coupon.access'),
				'company' => array('company.coupon.access'),
				'company.editor' => array('company.coupon.access'),
				'user.coupon.access' => array('coupon.access'),
				'company.coupon.access' => array('coupon.access'),
			)
		);
	}
}