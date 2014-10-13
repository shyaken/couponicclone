<?php
class WPaymentAffiliateUpdate extends UFormWorklet
{	
	public $modelClassName = 'MPaymentAffiliate';
	public $primaryKey='id';
	public $space = 'inside';
	
	public function title()
	{
		return $this->isNewRecord
			? $this->t('Add Affiliate Code')
			: $this->t('Edit Affiliate Code');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{
		$patterns = '{orderId} => order ID<br />';
		$patterns.= '{amount} => order amount<br />';
		$patterns.= '{productId} => deal ID (if multiple deals have been ordered at the same time it will replace this pattern with first found deal only)<br />';
		$patterns.= '{price} => deal coupon price (see notice above about multiple deals in an order)<br />';
		$patterns.= '{quantity} => coupons quantity<br />';
		$patterns.= '{gateway} => payment gateway that has been used to pay<br />';		
		
		$hint = $this->t('You can use following patterns: {patterns}', array(
			'{patterns}' => '<br />'.$patterns
		));
		
		return array(
			'description' => $this->t('Here you can add a code from affiliate software or service which will be automatically added to the "Thank you" page after successful payment.'),
			'action' => url('/payment/affiliate/update', array('id' => $this->model->id)),
			'elements' => array(
				'name' => array('type' => 'text'),
				'code' => array('type' => 'textarea', 'hint' => $hint),
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Add'):$this->t('Update')),
			),
			'model' => $this->model
		);
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('payment.affiliate.list');
		$message = $this->isNewRecord
			? $this->t('Affiliate code has been successfully added.')
			: $this->t('Affiliate code has been successfully updated.');
		$json = array(
			'info' => array(
				'replace' => $message,
				'fade' => 'target',
				'focus' => true,
			),
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$listWorklet->getDOMId(). '-grid")')
			),
		);
		if($this->isNewRecord)
			$json['load'] = url('/payment/affiliate/update', array('ajax'=>1,'id' => $this->model->id));
			
		wm()->get('base.init')->addToJson($json);
	}
}