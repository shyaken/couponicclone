<?php

class BDealPaymentOrder extends  UWorkletBehavior {

    public function afterPlace($items,$amount,$method,$result) {
		foreach($result->items as $item)
		{
			if($item->itemModule == 'deal')
			{
				$session = wm()->get("deal.helper")->session;
				if(isset($session['loc.'.$item->itemId]))
				{
					$m = new MPaymentOrderOptions;
					$m->itemId = $item->id;
					$m->type = 'redeemLocation';
					$m->name = 'Redeem Location';
					$m->value = wm()->get("deal.helper")->session["loc.".$item->itemId];
					$m->save();
				}
			}
		}
    }
}
