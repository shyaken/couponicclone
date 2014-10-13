<?php
class WSubscriptionAdminList extends UListWorklet
{
	public $addCheckBoxColumn=false;
	public $addButtonColumn=false;
	public $addMassButton=false;
	
	public $modelClassName = 'MSubscriptionList';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Subscription Lists');
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Title'), 'name' => 'title'),
			array('header' => $this->t('Type'), 'name' => 'type',
				'value' => 'wm()->get("subscription.admin.list")->type($data)',
				'filter' => wm()->get("subscription.admin.list")->types()),
			array('header' => $this->t('Subscribers'), 'value' => '$data->subs',
				'filter' => false),
		);
	}
	
	public function afterConfig()
	{
		$this->options = array(
			'selectableRows' => 1,
			'selectionChanged' => 'function(id) {
				var selection = $.fn.yiiGridView.getSelection(id);
				$("#'.$this->getDOMId().'").uWorklet().load({
					url: "'.url('/subscription/admin/emails').'?listId="+selection[0],
					position: "appendReplace"
				});
			}',
		);
		wm()->add('subscription.admin.newsletters', null, array(
			'position' => array('after' => $this->id)
		));
	}
	
	public function taskRenderOutput()
	{
		echo $this->t('Click on any row to see the list of subscribers.');
		parent::taskRenderOutput();
	}
	
	public function taskTypes()
	{
		return array(
			0 => $this->t('City Based'),
			1 => $this->t('Deal Based'),
			2 => $this->t('Category Based'),
			100 => $this->t('All Subscribers'),
		);
	}
	
	public function taskType($data)
	{
		$types = $this->types();
		return $types[$data->type];
	}
	
	public function afterBuild()
	{
		wm()->add('base.dialog');
	}
}