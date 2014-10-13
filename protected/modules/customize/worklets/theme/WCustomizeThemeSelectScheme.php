<?php
class WCustomizeThemeSelectScheme extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	
	public function title()
	{
		return $this->t('Current Scheme');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function theme()
	{
		return app()->themeManager->getTheme(app()->request->getParam('id',null));
	}
	
	public function afterConfig()
	{
		$model = MThemeColorScheme::model()->find('themeId=? AND current=?', array($this->theme()->name, 1));
		if($model)
			$this->model->attribute = $model->id;
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'attribute' => array(
					'type' => 'dropdownlist', 'items' => CHtml::listData(MThemeColorScheme::model()->findAll(new CDbCriteria(array(
						'condition' => 'themeId=?',
						'params' => array($this->theme()->name),
						'order' => 'name ASC',
					))), 'id', 'name'),
					'prompt' => $this->t('No Scheme'),
					'label' => $this->t('Select Scheme'),
				),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Set as Current'))
			),
			'model' => $this->model
		);
	}
	
	public function taskSave()
	{
		MThemeColorScheme::model()->updateAll(array('current' => 0), 'themeId=?', array($this->theme()->name));
		$m = MThemeColorScheme::model()->findByPk($this->model->attribute);
		if($m)
		{
			$m->current = 1;
			$m->save();
			wm()->get('customize.theme.helper')->dropCache($this->theme()->name);
		}
	}
	
	public function successUrl()
	{
		return url('/customize/theme/update', array('id' => $this->theme()->name));
	}
}