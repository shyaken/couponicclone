<?php
class WDealAdminParams extends UParamsWorklet
{	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'<h4>Categories</h4>',
				'categories' => array('type' => 'radiolist', 'items' => array(
					-1 => $this->t('Hide categories: filter deals by city only'),
					0 => $this->t('Filter deals by city and category'),
					1 => $this->t('Hide cities: filter deals by category only')
				), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>",
				'label' => $this->t('How to Use Categories')),
				'<h4>Newsletter</h4>',
				'requireSubscribe' => array('type' => 'radiolist', 'items' => array(
					1 => $this->t('Require visitors to subscribe to a newsletter before they can access the rest of the site'),
					0 => $this->t('Allow visitors to access the site without subscription'),
					-1 => $this->t('Disable subscription prompt')
				), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>",
				'label' => $this->t('Require Subscription')),
				'subscriptionDelete' => array('type' => 'text',
					'label' => $this->t('Deal Subscription Campaign Lifetime'),
					'layout' => "{label}\n<fieldset>{input}</fieldset>{hint}",
					'hint' => $this->t('Script automatically creates separate subscription lists for every deal that you create and subscribes all coupon buyers to it. Here you can specify how many days after the deal end should campaign exist. Put 0 to store it permanentally.')),
				'<h4>Company Payouts</h4>',
				'commission' => array('type' => 'text', 'hint' => '%',
					'label' => $this->t('Site Commission'),
					'class' => 'short', 'layout' => "{label}\n<span class='hintInlineAfter'>{input}\n{hint}</span>"),
				'<span class="hint">'.$this->t('Please specify which percentage from total amount raised from the deal will be held by the site.').'</span>',
				'payoutMode' => array(
					'type' => 'radiolist',
					'label' => $this->t('Payout Mode'),
					'items' => array(
						'redeem' => $this->t('Companies are paid for redeemed coupons only.'),
						'total' => $this->t('Companies are paid for all sold coupons.'),
					),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				),
				'<h4>Image Settings</h4>',
				'fileTypes' => array('type' => 'text', 'hint' => $this->t('ex.: jpg, gif, png'), 'label' => 'Supported Formats'),
				'fileSizeLimit' => array('type' => 'text', 'hint' => $this->t('MB'),
					'label' => $this->t('Maximum Filesize'),
					'class' => 'short', 'layout' => "{label}\n<span class='hintInlineAfter'>{input}\n{hint}</span>"),
				'fileResize' => array('type' => 'text', 'label' => $this->t('Resize Uploaded Images To'),
					'hint' => $this->t('ex.: 480x360')),
				'<h4>Misc Settings</h4>',
				'homepage' => array('type' => 'radiolist', 'items' => array(
						'deal.all' => $this->t('All Deals'),
						'deal.view' => $this->t('Featured Deal')),
					'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>",
					'label' => $this->t('Site Home Page')),
				'delimiter' => array('type' => 'text', 'label' => $this->t('Delimiter for CSV files'), 'class' => 'short'),
				'rssChannelDescription' => array('type'=>'text', 'label' => $this->t('Channel Description'), 'class' => 'large'),
				'upcoming' => array('type' => 'radiolist', 'items' => array(
					0 => $this->t('No'),
					1 => $this->t('Yes')
				), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>",
				'label' => $this->t('Show Upcoming Deals')),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function beforeSave()
	{
		if($this->model->categories >= 0 && $this->param('categories') < 0)
		{
			$subs = MSubscriptionEmail::model()->findAll();
			foreach($subs as $s)
			{
				$list = wm()->get('subscription.helper')->getList(2,0,true);
				$exists = MSubscriptionListEmail::model()->exists('listId=? AND emailId=?', array($list->id,$s->id));
				if(!$exists)
					wm()->get('subscription.helper')->addEmailToList($s->email, $list->id);
			}
		}
	}
}