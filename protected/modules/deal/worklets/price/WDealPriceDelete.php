<?php
class WDealPriceDelete extends UDeleteWorklet
{	
	public $modelClassName = 'MDealPrice';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeDelete($id)
	{
		if(!wm()->get('deal.edit.helper')->authorize($this->deal($id)))
		{
			$this->accessDenied();
			return false;
		}
		
		$m = MDealPrice::model()->findByPk($id);
		if($m->main)
			throw new CHttpException(403, $this->t('You can\'t delete main price option.'));

		$orders = MPaymentOrderItem::model()->count('itemModule=? AND itemId=?', array('deal',$id));
		if($orders)
			throw new CHttpException(403, $this->t('There is already an order associated with this price option. It can\'t be removed.'));
		
		MI18N::model()->deleteAll('model=? AND relatedId=?', array('DealPrice', $id));	
	}
	
	public function taskDeal($id)
	{
		static $deals=array();
		
		if(!isset($deals[$id]))
			$deals[$id] = MDealPrice::model()->findByPk($id)->deal;
		
		return $deals[$id];
	}
}