<?php
class BDealUserSignup extends UWorkletBehavior
{
	public $location;
	
	public function afterRegister($original,$model)
	{
		if(!$model->hasErrors())
		{
			$location = $this->location?$this->location:wm()->get('deal.helper')->location();
			wm()->get('subscription.helper')->addEmailToList($model->email,array(
				'type' => 0, 'relatedId' => $location
			));
		}
	}
}