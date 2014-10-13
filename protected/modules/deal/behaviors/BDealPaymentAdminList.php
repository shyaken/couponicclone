<?php
class BDealPaymentAdminList extends UWorkletBehavior
{
	public function afterItemsFilter($result)
	{
		$result['deal'] = $this->getModule()->t('Deals');
		return $result;
	}
}