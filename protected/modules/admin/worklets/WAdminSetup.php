<?php
class WAdminSetup extends UWidgetWorklet
{
	public $worklets = array();
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	/**
	 * Goes through all currently enabled modules
	 * and adds .admin.params worklets for all of them.
	 */
	public function taskConfig()
	{		
		$this->worklets[] = 'base.admin.appParams';
		$config = require($GLOBALS['config']);
		$modules = $config['modules'];
		$ws = array();
		foreach($modules as $name=>$module)
			$ws[] = $name;
		sort($ws);
		foreach($ws as $w)
			$this->worklets[] = $w . '.admin.params';
		parent::taskConfig();
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Setup') => url('/admin/setup')
		);
	}
	
	public function beforeBuild()
	{
		wm()->add('admin.setupMenu');
	}
	
	public function taskRenderOutput()
	{
		foreach($this->worklets as $w)
			app()->controller->worklet($w);
		
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().' .worklet .worklet-title").click(function(){
			$(this).closest(".worklet").find(" > .worklet-content").toggle();
			$(this).toggleClass("on");
		});');
	}
}