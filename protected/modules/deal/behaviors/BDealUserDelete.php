<?php
class BDealUserDelete extends UWorkletBehavior
{
	public function afterDelete($id)
	{
		MDealCoupon::model()->deleteAll('userId=?',array($id));
	}
}