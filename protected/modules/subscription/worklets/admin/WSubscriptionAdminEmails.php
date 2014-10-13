<?php
class WSubscriptionAdminEmails extends UListWorklet
{
	public $modelClassName = 'MSubscriptionListEmail';
	public $addCheckBoxColumn=false;
	public $addMassButton=false;
	
	public function title()
	{
		return $this->t('Subscribers');
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
		if(!$this->model->listId && isset($_GET['listId']))
			$this->model->listId = $_GET['listId'];
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Email'), 'name' => 'emailSearch', 'value' => '$data->email->email'),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'updateButtonUrl' => 'url("/subscription/admin/updateEmail", array("listId" => "'.$this->model->listId.'", "id" => $data->email->id))',
				'deleteButtonUrl' => 'url("/subscription/admin/removeEmail", array("id" => $data->primaryKey))',
				'buttons' => array(
					'update' => array('click' => 'function(){
						$.uniprogy.dialog($(this).attr("href"));
						return false;
					}'),					
				),
			),
		);
	}
	
	public function buttons()
	{
		$link = url('/subscription/admin/updateEmail', array('listId' => $this->model->listId));
		$linkImport = url('/subscription/admin/import', array('listId' => $this->model->listId));
		$id = $this->getDOMId().CHtml::ID_PREFIX.CHtml::$count++;
		$idImport = $this->getDOMId().CHtml::ID_PREFIX.CHtml::$count++;
		cs()->registerScript($this->getId().$id,'jQuery("#' .$id. '").click(function(e){
			e.preventDefault();
			$.uniprogy.dialog("'.$link.'");
		});');
		cs()->registerScript($this->getId().$idImport,'jQuery("#' .$idImport. '").click(function(e){
			e.preventDefault();
			$.uniprogy.dialog("'.$linkImport.'");
		});');
		return array(CHtml::button($this->t('Add Subscriber'), array('id' => $id)),
			CHtml::button($this->t('Import Subscribers'), array('id' => $idImport)));
	}
	
	public function beforeRenderOutput()
	{
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().'-grid a.delete").die();');
	}
}