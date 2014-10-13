<?php
class WBaseAdminParams extends UFormWorklet
{
	public function taskRenderOutput()
	{
		parent::taskRenderOutput();
		app()->controller->worklet('base.language.list');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return txt()->format(ucfirst($this->module->name),' ',$this->t('Module'));
	}
	
	public function properties()
	{
		$elements = array(
			'<h4>&quot;Follow Us&quot; Menu</h4>',
		);
		$follows = $this->module->params['follow'];
		$follows[] = null;
		
		foreach($follows as $k=>$v)
		{
			$model = $this->followForm($v);
			$elements['follow_'.$k] = array(
				'type' => 'UForm',
				'elements' => array(
					'name' => array('type' => 'text', 'label' => $this->t('Name'),
						'attributes' => array(
							'name' => get_class($model).'['.$k.'][name]',
							'id' => get_class($model).'_'.$k.'_name'
						)),
					'image' => array('type' => 'text', 'label' => $this->t('Image'),
						'attributes' => array(
							'name' => get_class($model).'['.$k.'][image]',
							'id' => get_class($model).'_'.$k.'_image'
						)),
					'url' => array('type' => 'text', 'label' => $this->t('URL'),
						'hint' => $this->t('URL or PHP expression; empty to remove provider'),
						'attributes' => array(
							'name' => get_class($model).'['.$k.'][url]',
							'id' => get_class($model).'_'.$k.'_url',
							'class' => 'large'
						)),
				),
				'model' => $model
			);
			$elements[] = '<hr />';
		}
		
		return array(
			'action' => url('/base/admin/params'),
			'elements' => $elements,
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function taskFollowForm($values=null)
	{
		$m = new MBaseFollowForm;
		if(is_array($values))
		{
			$m->name = $values[0];
			$m->image = $values[1];
			$m->url = $values[2];
		}
		return $m;
	}
	
	public function taskSave()
	{
		$follows = array();
		if(isset($_POST['MBaseFollowForm']) && is_array($_POST['MBaseFollowForm']))
		{
			foreach($_POST['MBaseFollowForm'] as $k=>$v)
				if($v['url'])
					$follows[] = array($v['name'],$v['image'],$v['url']);
		}
		$config = array();
		$config['modules']['base']['params']['follow'] = null;
		UHelper::saveConfig(app()->basePath.DS.'config'.DS.'public'.DS.'modules.php',$config);
		$config['modules']['base']['params']['follow'] = $follows;
		UHelper::saveConfig(app()->basePath.DS.'config'.DS.'public'.DS.'modules.php',$config);
	}
	
	public function successUrl()
	{
		return url('/admin/setup');
	}
}