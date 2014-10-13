<?php
class WAdminModules extends UListWorklet
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
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Setup') => url('/admin/setup'),
			$this->t('Manage Modules') => url('/admin/modules')
		);
	}
	
	public function beforeBuild()
	{
		if(isset($_POST['module']))
		{
			$this->changeStatus($_POST['module']);
			return $this->show = false;
		}
		wm()->add('admin.setupMenu');
	}
	
	public function afterConfig()
	{
		$this->options = array('enablePagination' => false, 'enableSorting' => false,
			'selectableRows' => 0);
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Module'), 'name' => 'title'),
			array('header' => $this->t('Action'),
				'value' => 'CHtml::ajaxButton(
					$data["enabled"]?"'.$this->t('Disable').'":"'.$this->t('Enable').'",
					url("/admin/modules"),array(
						"type" => "post",
						"data" => "module=".str_replace(".","%2E",$data["id"]),
						"success" => "js:function(){
							$.fn.yiiGridView.update(\"'.$this->getDOMId().'-grid\");
						}",
					))',
				'type' => 'raw'),
		);
	}
	
	public function dataProvider()
	{
		$config = require(Yii::getPathOfAlias('application.config.public.modules').'.php');
		wm()->get('install.helper')->loadModules();
		$modules = $this->getModulesInfo($config);
		return new CArrayDataProvider($modules, array(
			'pagination' => false
		));
	}
	
	public function getModulesInfo($config,$parent=null)
	{
		$modules = array();
		$numOfParents = 0;
		if(!$parent)
			$parent = app();
		else
			$numOfParents = substr_count(UFactory::getModuleAlias($parent),'.')+1;
		
		if(isset($config['modules']))
			foreach($config['modules'] as $id=>$cfg)
			{
				if(isset($cfg['params']['version']))
				{
					$m = $parent->getModule($id);
					if(!app()->getIsAppModule($id)
						|| ($parent!==app() && $parent->name == 'payment'))
						{
							$title = $m->getTitle();
							$p = $m->getParentModule();
							while($p)
							{
								$title = $p->getTitle().' > '.$title;
								$p = $p->getParentModule();
							}
							
							$modules[] = array(
								'id' => UFactory::getModuleAlias($m),
								'title' => $title,
								'enabled' => (boolean)(!isset($cfg['enabled']) || $cfg['enabled']),
								'parents' => $numOfParents,
							);
						}
					$modules = CMap::mergeArray($modules,$this->getModulesInfo($cfg,$m));
				}
			}
		return $modules;
	}
	
	public function taskChangeStatus($id)
	{
		$file = Yii::getPathOfAlias('application.config.public.modules').'.php';
		$modules = require($file);
		$trace = '["modules"]["'.str_replace('.','"]["modules"]["',$id).'"]["enabled"]';
		$enabled = (boolean)eval('return isset($modules'.$trace.') ? $modules'.$trace.' : true;');
		$enabled = $enabled ? '0' : '1';
		eval('$config'.$trace.' = "'.$enabled.'";');
		UHelper::saveConfig($file,$config);
	}
}