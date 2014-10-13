<?php
class BDealMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$this->getOwner()->insert('bottom',array(
			array('label'=>$this->t('All Deals'), 'url'=>array('/deal/all')),
			array('label'=>$this->t('Recent Deals'), 'url'=>array('/deal/recent')),
		));
	}
}