<?php
class WCustomizeCmsList extends UListWorklet
{
	public $modelClassName = 'MCmsPage';
	
	public function title()
	{
		return $this->t('Static Pages');
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
			array('header' => $this->t('Page Title'), 'name' => 'title'),
		);
	}
	
	public function buttons()
	{
		return array(
			$this->widget('UJsButton', array(
				'label' => $this->t('Create New Page'),
				'callback' => 'window.location = "'.url('/customize/cms/update').'";',
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
		wm()->add('customize.cms.listBlock', null, array('position' => array('after' => $this->id)));
	}
}