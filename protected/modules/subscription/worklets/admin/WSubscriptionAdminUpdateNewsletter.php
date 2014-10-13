<?php
class WSubscriptionAdminUpdateNewsletter extends UFormWorklet
{
	public $modelClassName = 'MSubscriptionCampaignForm';
	public $primaryKey = 'id';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->isNewRecord
			? $this->t('Create Newsletter')
			: $this->t('Update Newsletter');
	}
	
	public function properties()
	{
		$lists = array();
		foreach($this->lists() as $k=>$v)
		{
			$item = array('label' => $v, 'value' => $k);
			$lists[] = $item;
		}
		
		$minLength = count($lists) <= 10 ? 0 : 3;
		
		return array(
			'elements' => array(
				'listsField' => array(
					'type' => 'zii.widgets.jui.CJuiAutoComplete',
					'source' => $lists,
					'options' => array(
						'minLength' => $minLength,
						'select' => 'js:function( event, ui ) {
							$.uniprogy.subscription.addList(ui.item.label,ui.item.value);
							return false;
						}',
					),
					'hint' => '<div id="selectedLists"></div>',
				),
				'scheduleField' => array('type' => 'UDateTimePicker', 'htmlOptions'=>array('class'=>'medium')),
				'subject' => array('type' => 'text'),
				'plainBody' => array('type' => 'textarea'),
				'htmlBody' => array('type' => 'UCKEditor',
					'layout' => "<div class='clearfix'>{label}</div>{input}\n{hint}"),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Create'):$this->t('Update'))
			),
			'model' => $this->model
		);
	}
	
	public function beforeSave()
	{
		if(!isset($_POST['lists']) || !is_array($_POST['lists']) || !count($_POST['lists']))
			$this->model->addError('listsField', $this->t('Please add at least one subscription list to this campaign.'));
	}
	
	public function taskSave()
	{
		$data = $this->model->attributes;
		$data['schedule'] = UTimestamp::applyGMT($this->model->scheduleField,param('timeZone'));
		$data['lists'] = isset($_POST['lists']) && is_array($_POST['lists'])
			? $_POST['lists']
			: array();
		foreach($data['lists'] as $l)
		{
			$list = wm()->get('subscription.helper')->list($l);
			if($list->type == 100)
			{
				$data['lists'] = array($l);
				break;
			}
		}
		wm()->get('subscription.helper')->addCampaign($data);
	}
	
	public function taskRenderOutput()
	{
		$lists = array();
		if(count($this->model->lists))
		{
			foreach ($this->model->lists as $l)
				$lists[$l->id] = $l->title;
		}
		$script = 'jQuery("#wlt-BaseDialog").dialog("option", "width", 1000);$.uniprogy.subscription.init('.CJavaScript::encode($lists).','.count($this->lists()).');';
		cs()->registerScript(__CLASS__,$script);
		parent::taskRenderOutput();
	}
	
	public function taskLists()
	{
		static $lists;
		if(!isset($lists))			
			$lists =  CHtml::listData(MSubscriptionList::model()->findAll(new CDbCriteria(array(
				'order' => 'title ASC',
			))),'id','title');
		return $lists;
	}
	
	public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array(
			'info' => array(
				'replace' => $this->t('Newsletter has been successfully updated.'),
				'fade' => 'target',
				'focus' => true,
			),
		));
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		$r[$this->t('Subscriptions')] = url('/subscription/admin/list');
		$r[] = $this->model->subject?$this->model->subject:$this->t('Create Newsletter');
		return $r;
	}
}