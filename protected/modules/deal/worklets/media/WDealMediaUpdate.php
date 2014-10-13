<?php
class WDealMediaUpdate extends UFormWorklet
{	
	public $modelClassName = 'MDealMediaForm';
	public $primaryKey='id';
	public $deal;
	
	public function title()
	{
		return $this->isNewRecord
			? $this->t('Add Deal Media')
			: $this->t('Edit Deal Media');
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
		if(!$this->deal)
			if(isset($_GET['id']))
				$this->deal = MDealMedia::model()->findByPk($_GET['id'])->deal;
			elseif(isset($_GET['dealId']))
				$this->deal = MDeal::model()->findByPk($_GET['dealId']);
		return $this->deal;
	}
	
	public function properties()
	{			
		$this->model->image = $this->deal()->image;
		return array(
			'elements' => array(
				'dealId' => array('type' => 'hidden'),
				'type' => array('type' => 'radiolist', 'items' => array(
					1 => $this->t('Image'),
					2 => $this->t('Embed Code (video)'),
				), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
				'<div class="mediaImage">',
				'image' => array('type' => 'UUploadField', 'attributes' => array(
					'label' => $this->t('Upload'),
					'url' => url('/deal/image',
						array(
							'binField'=>CHtml::getIdByName(CHtml::activeName($this->model,'image')),
							'id'=>app()->request->getParam('dealId',null),
						)),
				),	'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}"),
				'</div><div class="mediaEmbed">',
				'embed' => array('type' => 'textarea'),
				'</div>',
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Create'):$this->t('Update'),
					'id' => 'submitButton'),
			),
			'model' => $this->model
		);
	}
	
	public function afterCreateForm()
	{
		$this->model->dealId = $this->deal()->id;
		if(!$this->model->dealId)
			$this->accessDenied();		
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
		if(!app()->request->isAjaxRequest)
			wm()->add('base.dialog');
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('deal.media.list');
		$message = $this->isNewRecord
			? $this->t('Deal media has been successfully added.')
			: $this->t('Deal media has been successfully updated.');
		$json = array(
			'info' => array(
				'replace' => $message,
				'fade' => 'target',
				'focus' => true,
			),
			'content' => array(
				'append' => CHtml::script('$.fn.yiiListView.update("' .$listWorklet->getDOMId(). '-list");
				$("#wlt-DealSlideshow-1").uWorklet().load({url: "'.url('/deal/slideshow',array('dealId'=>$this->deal()->id)).'"});')
			),
		);
		$json['load'] = url('/deal/media/update', array('ajax'=>1, 'dealId' => $this->deal()->id));
		wm()->get('base.init')->addToJson($json);
	}
	
	public function taskRenderOutput()
	{
		parent::taskRenderOutput();
		$att = 'type';
		$name = CHtml::resolveName($this->model,$att);
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().' input[name=\''.$name.'\']:radio").change(function(){
			$("#submitButton").hide();
			$(".mediaImage").hide();
			$(".mediaEmbed").hide();
			if($(this).is(":checked"))
			{
				if($(this).val() == "1")
					$(".mediaImage").show();
				else if($(this).val() == "2")
				{
					$("#submitButton").show();
					$(".mediaEmbed").show();
				}
			}
		});jQuery("#'.$this->getDOMId().' input[name=\''.$name.'\']:radio").change();');
	}
}