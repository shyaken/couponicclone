<?php
class WAgentCitymanagerUpdateLevel extends UFormWorklet
{	
	public $modelClassName = 'MCitymanagerLevelForm';
	public $primaryKey='id';
	public $space = 'inside';
	
	public function title()
	{
		return $this->isNewRecord
			? $this->t('New Access Level')
			: $this->t('Edit Access Level');
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
		if($this->isNewRecord)
		{
			$this->model->allLocations = 0;
			$this->model->level = 0;
		}
		else
		{
			if(!$this->model->location)
				$this->model->allLocations = 1;
			else
				$this->model->allLocations = 0;
		}
				
		return array(
			'elements' => array(
				'level' => array('type' => 'radiolist', 'items' => array(
					0 => $this->t('can edit only those deals that belong to the manager'),
					1 => $this->t('can edit all deals')
				), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
				'allLocations' => array('type' => 'radiolist', 'items' => array(
					1 => $this->t('All Locations'),
					0 => $this->t('Certain Location'),
				), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
				'divOpen' => '<div id="locationForm" style="display:none">',
				'divClose' => '</div>',
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Add'):$this->t('Save')),
			),
			'model' => $this->model
		);
	}
	
	public function beforeBuild()
	{
		$b = $this->attachBehavior('location.form','location.form');
		$b->insert = array('after' => 'divOpen');
		$b->required = false;
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
	
	public function beforeSave()
	{
		if(!$this->model->allLocations && !$this->model->location)
		{
			$this->model->addError('allLocations', $this->t('Please specify location.'));
			return false;
		}
		
		$this->model->userId = $_GET['userId'];
		if($this->model->allLocations)
			$this->model->location = 0;
			
		$m = MCitymanager::model()->find('userId=? AND location=?', array(
			$_GET['userId'],
			$this->model->location
		));
		if($m && $m->id != $this->model->id)
		{
			$this->model->addError('allLocations', $this->t('Access to this location has been already provided to this manager.'));
			return false;
		}
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('agent.citymanager.update');
		$json = array(
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$listWorklet->getDOMId(). '-grid");
					$("#'.$this->id.'").closest(".worklet-pushed-content").remove();')
			),
		);
			
		wm()->get('base.init')->addToJson($json);
	}
	
	public function taskRenderOutput()
	{
		$att = 'allLocations';
		$name = CHtml::resolveName($this->model,$att);
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().' input[name=\''.$name.'\']:radio").change(function(){
			if($(this).is(":checked"))
			{
				if($(this).val() == "0")
					$("#locationForm").show();
				else
					$("#locationForm").hide();
			}
		});jQuery("#'.$this->getDOMId().' input[name=\''.$name.'\']:radio").change();');
		parent::taskRenderOutput();
	}
}