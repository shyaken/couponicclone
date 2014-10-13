<?php
class WDealReviewUpdate extends UFormWorklet
{	
	public $modelClassName = 'MDealReviewForm';
	public $primaryKey='id';
	public $space = 'inside';
	
	public function title()
	{
		return $this->isNewRecord
			? $this->t('Add Deal Review')
			: $this->t('Edit Deal Review');
	}
	
	public function beforeAccess()
	{
		if($this->deal() && wm()->get('deal.edit.helper')->authorize($this->deal()))
			return true;
		$this->accessDenied();
		return false;
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskDeal()
	{
		static $deal;
		if(!isset($deal))
			if(isset($_GET['id']))
				$deal = MDealReview::model()->findByPk($_GET['id'])->deal;
			else
				$deal = isset($_GET['dealId'])
					? MDeal::model()->findByPk($_GET['dealId'])
					: null;
		return $deal;
	}
	
	public function properties()
	{			
		return array(
			'elements' => array(
				'dealId' => array('type' => 'hidden'),
				'name' => array('type' => 'text'),
				'website' => array('type' => 'text'),
				'review' => array('type' => 'textarea'),
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Create'):$this->t('Update')),
			),
			'model' => $this->model
		);
	}
	
	public function afterCreateForm()
	{
		$this->form->model->dealId = $this->deal()->id;
		if(!$this->form->model->dealId)
			$this->accessDenied();		
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('deal.review.list');
		$message = $this->isNewRecord
			? $this->t('Deal review has been successfully added.')
			: $this->t('Deal review has been successfully updated.');
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
			$json['load'] = url('/deal/review/update', array('ajax'=>1,'id' => $this->model->id));
			
		wm()->get('base.init')->addToJson($json);
	}
}