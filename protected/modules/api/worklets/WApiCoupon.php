<?php
class WApiCoupon extends UApiWorklet
{
	public function afterConfig()
	{
		
		if (!app()->user || app()->user->isGuest)
			$this->errorMessage = $this->t('Authentication required.');
		
		$code = $this->getRequiredParam('code');
		
		if(!intval($code))
			$this->errorMessage = $this->t('Invalid code value.');

		$language = app()->request->getParam('language',null); 
		
		if(!is_null($language))
			app()->language = $language;
		
		$coupon = MDealCoupon::model()->find('redemptionCode='.$code );
		
		if(!$coupon)
			$this->errorMessage = $this->t('Coupon can not be found.');

		if(!app()->user->checkAccess('coupon.access',$coupon))
			$this->errorMessage = $this->t('Access denied.');
			
		$status = "available";
		if($coupon->status == 2)
			$status = "used";
		if($coupon->deal->expire && $coupon->deal->expire <= time())
			$status = "expired";
		
		$data = array();
		$data['coupon']['id'] = $coupon->id;
		$data['coupon']['hashid'] = '#'.$coupon->dealId.'-'.$coupon->orderId.'-'.$coupon->hash;
		$data['coupon']['deal'] = $coupon->price->deal->name;
		$data['coupon']['name'] = $coupon->user->name;
		$data['coupon']['firstname'] = $coupon->user->firstName;
		$data['coupon']['lastname'] = $coupon->user->lastName;
		$data['coupon']['status'] = $status;
		$data['coupon']['code'] = $coupon->redemptionCode;
		

		$this->data = $data;
	}
	
}
