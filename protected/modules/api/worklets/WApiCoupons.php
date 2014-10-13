<?php
class WApiCoupons extends UApiWorklet
{
	public function afterConfig()
	{
		if (app()->user->isGuest)
			$this->errorMessage = 'Authentication required';
		
        $language = $this->getRequiredParam('language');
        $langs = wm()->get('base.language')->languages();
		if(!isset($langs[$language]))
			$this->errorMessage = 'No such language.';
		app()->language = $language;

		$type = app()->request->getParam('type',null);
		$type = is_null($type)?'available':$type;

		$model = new MDealPaymentOrderModel;
		if($model)
		{
			$model->userId = app()->user->id;
			$model->status = '>0';
			switch($type){
				case 'available':
					$model->hasUsed = false;
					$model->expired = false;
					break;
				case 'used':
					$model->hasUsed = true;
					break;
				case 'expired':
					$model->hasUsed = false;
					$model->expired = true;
					break;
			}
		}
		
		$prices = $model->search()->data;
		//$model->findAll($model->search()->criteria);
		
		$data = array();
        $key = 0;
		foreach ($prices as $price) {
			$deal = $price->deal;
			foreach ($price->getAllCoupons(app()->user->id,$model->hasUsed) as $value){
                $data['coupons'][$key]['coupon']['company'] = array('id' => $deal->companyId,
                                                          'name' => $deal->company->name,
                                                          'address' => $deal->company->address,
                                                          'website' => $deal->company->website);

				$data['coupons'][$key]['coupon']['id'] = $value->id;
                $data['coupons'][$key]['coupon']['hashid'] = '#'.$value->dealId.'-'.$value->orderId.'-'.$value->hash;
                $data['coupons'][$key]['coupon']['coupon_status'] = $type;
				$data['coupons'][$key]['coupon']['title'] = $deal->name;
                $data['coupons'][$key]['coupon']['date'] = date('Y-m-d H:i',$value->order->created);
				$data['coupons'][$key]['coupon']['printable'] = $value->order->status == 2 ? 'true' : 'false';
                
                if($deal->image) {
                    $img = app()->storage->bin($deal->image)->getFileUrl('original_t');
                    if (!$img)
                        $img = app()->storage->bin($deal->image)->getFileUrl('original');
                    $data['coupons'][$key]['coupon']['image'] = $img;
                }
				
				$data['coupons'][$key]['coupon']['fine_print'] = $deal->fineprint;
				$data['coupons'][$key]['coupon']['code'] = $value->redemptionCode;
				$data['coupons'][$key]['coupon']['barcode'] = aUrl('/deal/barcode/', array('barcode' => $value->redemptionCode));
				foreach ($deal->redeemLocs as $k=>$redeem){
					$data['coupons'][$key]['coupon']['redeem'][$k]['loc']['id'] = $redeem->id;
					$data['coupons'][$key]['coupon']['redeem'][$k]['loc']['address'] = wm()->get("deal.location.redeemList")->loc($redeem);
					$data['coupons'][$key]['coupon']['redeem'][$k]['loc']['lon'] = $redeem->lon;
					$data['coupons'][$key]['coupon']['redeem'][$k]['loc']['lat'] = $redeem->lat;
				}		
                $key++;
			}
            
		}
		
		$this->data = $data;
	}
	
}
