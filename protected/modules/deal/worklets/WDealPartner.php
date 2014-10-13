<?php
class WDealPartner extends UFormWorklet
{
	public $modelClassName = 'MDealPartnerForm';
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function title()
	{
		return $this->t('Become a Partner');
	}
	
	public function properties()
	{
		$this->model->location = wm()->get('deal.helper')->location();
		return array(
			'elements' => array(
				'companyName' => array('type' => 'text'),
				'firstName' => array('type' => 'text'),
				'lastName' => array('type' => 'text'),
				'email' => array('type' => 'text'),
				'address' => array('type' => 'text'),
				'phone' => array('type' => 'text'),
				'website' => array('type' => 'text'),
				'reviews' => array('type' => 'textarea'),
				'about' => array('type' => 'textarea'),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Submit'))
			),
			'model' => $this->model
		);
	}
	
	public function beforeBuild()
	{
		$this->attachBehavior('base.captcha','base.captcha');
		$b = $this->attachBehavior('location.form', 'location.form');
		$b->ignoreFixed = true;
	}
	
	public function taskSave()
	{
		app()->mailer->send(param('contactEmail'), 'partnerEmail', array('model' => $this->model));
	}
	
	public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array(
			'info' => array(
				'replace' => $this->t('Thank you for your request! We will get back to you shortly.'),
				'focus' => true
			),
			'content' => array(
				'replace' => '<!-- -->',
			),
		));
	}
}