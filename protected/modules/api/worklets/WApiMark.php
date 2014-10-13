<?php
class WApiMark extends UApiWorklet
{
	public function afterConfig()
	{
		if (app()->user->isGuest)
			$this->errorMessage = $this->t('Authentication required.');
		
		$id = $this->getRequiredParam('id');
		$code = $this->getRequiredParam('code');
        if(!intval($id))
			$this->errorMessage = $this->t('Invalid id value.');
        if(!intval($code))
			$this->errorMessage = $this->t('Invalid code value.');
        
		$coupon = MDealCoupon::model()->find('redemptionCode='.$code.' and id='.$id);
		
        if(!$coupon)
			$this->errorMessage = $this->t('Coupon can not be found.');
		
        if(!app()->user->checkAccess('coupon.access',$coupon))
			$this->errorMessage = $this->t('Access denied.');
		
		if(app()->user->checkAccess('company.coupon.access',$coupon))
			$statusField = 'status';
		elseif(app()->user->checkAccess('user.coupon.access',$coupon))
			$statusField = 'userStatus';
		
		$coupon->$statusField = 2;
		$coupon->save();

	}
	
}
