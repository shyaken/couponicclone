<?php
class WSubscriptionAdminNewsletters extends UListWorklet
{
	public $modelClassName = 'MSubscriptionCampaign';
	public $addCheckBoxColumn=false;
	public $addMassButton=false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Newsletters');
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Subject'), 'name' => 'subject'),
			array('header' => $this->t('Schedule'), 'name' => 'schedule',
				'value' => 'app()->dateFormatter->formatDateTime(
					utime($data->schedule,false),"short","short")'),
			array('header' => $this->t('Emails Sent'), 'name' => 'complete',
				'value' => '$data->complete<0?-($data->complete+1):$data->complete'),
			array('header' => $this->t('Status'), 'name' => 'status',
				'value' => 'wm()->get("subscription.admin.newsletters")->status($data)',
				'filter' => $this->statusList()),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'updateButtonUrl' => 'url("/subscription/admin/updateNewsletter", array("id" => $data->primaryKey))',
				'deleteButtonUrl' => 'url("/subscription/admin/removeNewsletter", array("id" => $data->primaryKey))',					
			),
		);
	}
	
	public function buttons()
	{
		$link = url('/subscription/admin/updateNewsletter');
		$id = $this->getDOMId().CHtml::ID_PREFIX.CHtml::$count++;
		cs()->registerScript($this->getId().$id,'jQuery("#' .$id. '").click(function(e){
			e.preventDefault();
			window.location = "'.$link.'";
		});');
		return array(CHtml::button($this->t('Create Newsletter'), array('id' => $id)));
	}
	
	public function taskStatusList()
	{
		return array(
			1 => $this->t('Scheduled'),
			2 => $this->t('Running'),
			3 => $this->t('Complete'),
		);
	}
	
	public function taskStatus($data)
	{
		if($data->complete < 0)
			return $this->t('Complete');
		else
		{
			if($data->schedule < time())
				return $this->t('Running');
			return $this->t('Scheduled');
		}
	}
}