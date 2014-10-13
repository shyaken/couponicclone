<?php
class WInstallHome extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Installer Home');
	}
	
	public function properties()
	{
		return array(
			'elements' => array('attribute' => array('type' => 'hidden')),
			'model' => $this->model
		);
	}
	
	public function taskConfig()
	{
		wm()->get('install.helper')->loadModules();
		wm()->get('install.patch')->patch();
		
		return parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{
		$assets = asma()->publish(Yii::getPathOfAlias('zii.widgets.assets.gridview'));
		cs()->registerCssFile($assets.'/styles.css');
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->form->id.' input[type=\'submit\']").click(function(){
			$("#'.$this->modelClassName.'_attribute").val($(this).attr("name"));
		});');
		
		$app = array(
			'id' => 'app',
			'title' => app()->getTitle(),
			'version' => app()->getVersion(),
			'install' => !param('version'),
			'upgrade' => param('version') && version_compare(param('version'), app()->getVersion()) < 0,
			'parents' => 0,
		);
		
		echo $this->form->renderBegin();
		$this->render('home',array('app' => $app, 'appModules' => $this->findModules(true), 'customModules' => $this->findModules(false)));
		echo $this->form->renderBody() . $this->form->renderEnd();
	}
	
	public function taskFindModules($appModules,$parent=null)
	{
		$modules = array();
		$parent = $parent?$parent:app();
		$numOfParents = $parent===app()?0:substr_count(UFactory::getModuleAlias($parent),'.')+1;
		foreach($parent->modules as $id=>$cfg)
		{
			$module = $parent->getModule($id);
			if($module == $this->module)
				continue;
			if(($appModules && app()->getIsAppModule($module))
				 || (!$appModules && !app()->getIsAppModule($module)))
			{			
				$info = array(
					'id' => UFactory::getModuleAlias($module),
					'title' => $module->getTitle(),
					'version' => $module->getVersion(),
					'install' => !$module->param('version'),
					'upgrade' => $module->param('version')
						? version_compare($module->param('version'), $module->getVersion()) < 0
						: false,
					'parents' => $numOfParents,
				);
				$key = $module->name;
				$modules[$key] = $info;
			}
			$modules = CMap::mergeArray($modules,$this->findModules($appModules,$module));
		}
		asort($modules);
		return $modules;
	}
	
	public function taskSave()
	{
		$this->successUrl = url('/install/confirm',array('install' => $this->model->attribute));
	}
}