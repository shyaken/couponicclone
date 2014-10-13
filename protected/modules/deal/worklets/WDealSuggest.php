<?php
class WDealSuggest extends UFormWorklet
{
	public $modelClassName = 'MDealSuggestForm';
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function title()
	{
		return $this->t('Suggest a Business');
	}
	
	public function properties()
	{
		$this->model->location = wm()->get('deal.helper')->location();
		return array(
			'elements' => array(
				'name' => array('type' => 'text'),
				'website' => array('type' => 'text'),
				'review' => array('type' => 'textarea'),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Suggest'))
			),
			'model' => $this->model
		);
	}
	
	public function beforeBuild()
	{
		$this->attachBehavior('base.captcha','base.captcha');
		$b = $this->attachBehavior('location.form', 'location.form');
		$b->insert = array('after' => 'website');
		$b->ignoreFixed = true;
	}
	
	public function taskSave()
	{
		app()->mailer->send(param('contactEmail'), 'suggestEmail', array('model' => $this->model));
	}
	
	public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array(
			'info' => array(
				'replace' => $this->t('Thank you for your suggestion!'),
				'focus' => true
			),
			'content' => array(
				'replace' => '<!-- -->',
			),
		));
	}
}