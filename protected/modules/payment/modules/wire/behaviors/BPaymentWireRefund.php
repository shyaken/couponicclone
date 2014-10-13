<?php
class BPaymentWireRefund extends UWorkletBehavior
{
	public function beforeSave()
	{
		if($this->owner->form->clicked('void') && $this->owner->model->method == 'wire'
			&& $this->owner->model->status == 2)
		{
			wm()->get('payment.wire.refund')->run($this->owner->model);
			return true;
		}
	}
}