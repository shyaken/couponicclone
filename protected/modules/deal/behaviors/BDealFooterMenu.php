<?php
class BDealFooterMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$this->getOwner()->insertAfter($this->owner->t('Home'),array(
			array('label'=>$this->t('Suggest a Business'), 'url'=>array('/deal/suggest')),
			array('label'=>$this->t('Become a Partner'), 'url'=>array('/deal/partner')),
		));
	}
}