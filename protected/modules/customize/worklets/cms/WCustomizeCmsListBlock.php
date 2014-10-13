<?php
class WCustomizeCmsListBlock extends UListWorklet
{
	public $modelClassName = 'MCmsBlock';
	public $addMassButton = false;
	
	public function title()
	{
		return $this->t('Blocks');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Block Title'), 'name' => 'title'),
			'buttons' => array(				
				'class' => 'CButtonColumn',
				'updateButtonUrl' => 'url("'.$this->getParentPath().'/updateBlock",array("id"=>$data->primaryKey))',
				'deleteButtonUrl' => 'url("'.$this->getParentPath().'/deleteBlock",array("id"=>$data->primaryKey))'
			),
		);
	}
	
	public function buttons()
	{
		return array(
			CHtml::ajaxSubmitButton($this->t('Delete'), url($this->getParentPath().'/deleteBlock'), array(
				'success' => 'function(){$.fn.yiiGridView.update("' .$this->getDOMId(). '-grid");}',
			)),				
			$this->widget('UJsButton', array(
				'label' => $this->t('Create New Block'),
				'callback' => 'window.location = "'.url('/customize/cms/updateBlock').'";',
			), true)
		);
	}
	
	public function taskBreadCrumbs()
	{
		$bC = array();
		$bC[$this->t('Customize')] = url('/customize');
		$bC[$this->t('CMS')] = url('/customize/cms/list');
		return $bC;
	}
	
	public function afterBuild()
	{
		wm()->add('customize.cms.blockList');
	}
}