<?php
class WDealInstallUpgrade_1_5_0 extends UInstallWorklet
{
	public $fromVersion = '1.4.4';
	public $toVersion = '1.5.0';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
			'homepage' => 'deal.view',
			'subscriptionDelete' => '0',
			'payoutMode' => 'total',
		));
	}
	
	public function taskSuccess()
	{
		// default language
		$language = wm()->get('base.helper')->defaultConfig('language');
		if(!$language)
			$language = app()->sourceLanguage;
		
		// moving deal i18n data to universal i18n table
		$models = MDealI18N::model()->findAll();
		foreach($models as $m)
		{
			$n = new MI18N;
			$n->model = 'Deal';
			$n->relatedId = $m->dealId;
			$n->language = $m->language;
			$n->name = $m->name;
			$n->value = $m->value;
			$n->save();			
		}
		
		// turn category names into i18n format		
		$categories = MDealCategory::model()->findAll();
		foreach($categories as $c)
		{
			$m = new MI18N;
			$m->model = 'DealCategory';
			$m->relatedId = $c->id;
			$m->language = $language;
			$m->name = 'name';
			$m->value = $c->name;
			$m->save();
		}		
		app()->db->createCommand("ALTER TABLE `{{DealCategory}}` DROP `name`")->execute();
		
		// moving deal prices and values to separate table
		$deals = MDeal::model()->findAll();
		foreach($deals as $d)
		{
			$m = new MDealPrice;
			$m->dealId = $d->id;
			$m->price = $d->price;
			$m->value = $d->value;
			$m->main = 1;
			$m->save();
			
			// updating i18n data
			MI18N::model()->updateAll(array(
				'relatedId' => $m->id,
				'model' => 'DealPrice'
			), 'relatedId=? AND model=? AND name=?', array($d->id, 'Deal', 'name'));
			
			// generate deal name - for admin search
			$name = MI18N::model()->find('model=? AND name=? AND relatedId=? AND language=?', array(
				'DealPrice', 'name', $m->id, $language
			));
			
			if($name)			
				wm()->get('deal.edit.helper')->dealName($d->id, array($language => $name->value));
			
			// update coupons
			MDealCoupon::model()->updateAll(array(
				'priceId' => $m->id
			), 'dealId=?', array($d->id));
			
			// update payment order items
			MPaymentOrderItem::model()->updateAll(array(
				'itemId' => $m->id
			), 'itemId=? AND itemModule=?', array($d->id,'deal'));
		}
		app()->db->createCommand("ALTER TABLE `{{Deal}}` DROP `value` , DROP `price`")->execute();
		
		parent::taskSuccess();
	}
}