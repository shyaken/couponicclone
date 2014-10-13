<?php
class WCustomizeThemeList extends UListWorklet
{
	public $addCheckBoxColumn=false;
	public $addButtonColumn=false;
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
		return $this->t('Themes');
	}
	
	public function afterConfig()
	{
		$this->options = array('template' => "{items}\n{pager}");
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Theme'), 'name' => 'name'),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'template' => '{update}',
				'updateButtonUrl' => 'url("'.$this->getParentPath().'/update",array("id"=>$data["id"]))'
			),
		);
	}
	
	public function filter()
	{
		return null;
	}
	
	public function dataProvider()
	{
		$themes = wm()->get('customize.theme.helper')->list();
		return new CArrayDataProvider($themes);
	}
	
	public function beforeBuild()
	{
		wm()->add('customize.theme.current');
	}
	
	public function taskBreadCrumbs()
	{
		$bC = array();
		$bC[$this->t('Customize')] = url('/customize');
		$bC[$this->t('Themes')] = url('/customize/theme/list');
		return $bC;
	}
}