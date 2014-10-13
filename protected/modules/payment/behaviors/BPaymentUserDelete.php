<?php
class BPaymentUserDelete extends UWorkletBehavior
{
	public function afterDelete($id)
	{
		MPaymentCredit::model()->deleteAll('id=?',array($id));
		$models = MPaymentOrder::model()->findAll('userId=?',array($id));
		foreach($models as $m)
		{
			MPaymentOrderItem::model()->deleteAll('orderId=?',array($m->id));
			$m->delete();
		}
	}
}