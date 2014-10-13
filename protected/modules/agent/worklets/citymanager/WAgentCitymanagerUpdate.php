<?php
class WAgentCitymanagerUpdate extends UListWorklet
{	
	public $modelClassName = 'MCitymanager';
	public $space = 'inside';
	public $addMassButton = false;
	
	public function title()
	{
		return $this->t('{name}: Access Level', array(
			'{name}' => MUser::model()->findByPk($_GET['id'])->getName(true)
		));
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function afterConfig()
	{
		$this->model->userId = $_GET['id'];
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Location'), 'value' => 'wm()->get("agent.citymanager.update")->location($data)'),
			array('header' => $this->t('Access Level'), 'value' => 'wm()->get("agent.citymanager.update")->level($data)'),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'buttons' => array(
					'update' => array('click' => 'function(){
						$("#' .$this->getDOMId(). '").uWorklet().load({position:"appendReplace",url: $(this).attr("href")});
						return false;}'),					
				),
				'updateButtonUrl' => 'url("/agent/citymanager/updateLevel", array("userId" => $data->userId, "id" => $data->primaryKey))',
				'deleteButtonUrl' => 'url("/agent/citymanager/deleteLevel", array("id" => $data->primaryKey))',
			),
		);
	}
	
	public function filter()
	{
		return null;
	}
	
	public function buttons()
	{
		$link = url('/agent/citymanager/updateLevel', array('userId' => $_GET['id']));
		$id = $this->getDOMId().CHtml::ID_PREFIX.CHtml::$count++;
		cs()->registerScript($this->getId().$id,'jQuery("#' .$id. '").click(function(e){
		e.preventDefault();
		$.uniprogy.loadingButton("#'.$id.'",true);
		$("#' .$this->getDOMId(). '").uWorklet().load({
			url: "' .$link. '",
			position: "appendReplace", 
			success: function(){
				$.uniprogy.loadingButton("#'.$id.'",false);
			}
		});
		});');
		return array(
			CHtml::ajaxSubmitButton($this->t('Delete'), url($this->getParentPath().'/deleteLevel'), array(
				'success' => 'function(){$.fn.yiiGridView.update("' .$this->getDOMId(). '-grid");}',
			)),
			CHtml::button($this->t('Add New Level'), array('id' => $id))
		);
	}
	
	public function taskLocation($data)
	{
		return $data->location == 0
			? $this->t('All Locations')
			: wm()->get("location.helper")->locationAsText($data->loc,false,false," ");
	}
	
	public function taskLevel($data)
	{
		switch($data->level)
		{
			case '0':
				return $this->t('Only deals that manager owns');
				break;
			case '1':
				return $this->t('All deals within the city');
				break;
		}
	}
}