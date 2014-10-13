<?php
class WCustomizeThemeUpdateScheme extends UFormWorklet
{
	public $modelClassName = 'MThemeColorScheme';
	public $primaryKey = 'id';
	public $space = 'inside';
	
	public function title()
	{
		return $this->isNewRecord
			? $this->t('Add New Scheme')
			: $this->t('Edit Color Scheme');
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
		return $this->isNewRecord
			? app()->themeManager->getTheme(app()->request->getParam('themeId',null))
			: app()->themeManager->getTheme($this->model->themeId);
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'name' => array('type' => 'text'),
				'colors' => $this->render('colors', array(
					'default' => wm()->get('customize.theme.helper')->colors($this->theme()),
					'scheme' => $this->model
				), true),
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove();$(".colorpicker").remove();'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Create'):$this->t('Save')),
			),
			'model' => $this->model
		);
	}
	
	public function taskSave()
	{
		foreach($_POST['colors'] as $k=>$v)
			$_POST['colors'][$k] = '#'.$v;
		$this->model->value = serialize($_POST['colors']);
		$this->model->themeId = $this->theme()->name;
		if(!$this->model->current)
			$this->model->current = 0;
		else
			wm()->get('customize.theme.helper')->dropCache($this->theme()->name);
		parent::taskSave();
	}
	
	public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array(
			'content' => array('append' => CHtml::script('location.reload(true);'))
		));
	}
}