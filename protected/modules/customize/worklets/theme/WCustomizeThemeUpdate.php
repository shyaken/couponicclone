<?php
class WCustomizeThemeUpdate extends UListWorklet
{
	public $modelClassName = 'MThemeColorScheme';
	public $addCheckBoxColumn = false;
	public $addMassButton=false;
	
	public function title()
	{
		return $this->t('Configure Theme: {theme}', array(
			'{theme}' => $this->theme()->themeName,
		));
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
		$this->model->themeId = $this->theme()->name;
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Color Scheme'), 'name' => 'name'),
			'buttons' => array(				
				'class' => 'CButtonColumn',
				'template' => '{update} {delete}',
				'buttons' => array(
					'update' => array('click' => 'function(){
						$("#' .$this->getDOMId(). '").uWorklet().load({position:"appendReplace",url: $(this).attr("href")});
						return false;}'),
				),
				'updateButtonUrl' => 'url("'.$this->getParentPath().'/updateScheme",array("id"=>$data->primaryKey))',
				'deleteButtonUrl' => 'url("'.$this->getParentPath().'/deleteScheme",array("id"=>$data->primaryKey))'
			),
		);
	}
	
	public function filter()
	{
		return null;
	}
	
	public function buttons()
	{
		$link = url('/customize/theme/updateScheme',array('themeId'=>$this->theme()->name));
		$id = $this->getDOMId().CHtml::ID_PREFIX.CHtml::$count++;
		cs()->registerScript($this->getId().$id,'jQuery("#' .$id. '").click(function(e){
		e.preventDefault();
		$.uniprogy.loadingButton("#'.$id.'",true);
		$("#' .$this->getDOMId(). '").uWorklet().load({
			url: "' .$link. '",
			position: "appendReplace", 
			success: function(){
				$.uniprogy.loadingButton("#'.$id.'",false);
			}
		});
		});');
		return array(CHtml::button($this->t('Create New Scheme'), array('id' => $id)));
	}
	
	public function taskBreadCrumbs()
	{
		$bC = array();
		$bC[$this->t('Customize')] = url('/customize/list');
		$bC[$this->t('Themes')] = url('/customize/theme/list');
		$bC[] = $this->title;
		return $bC;
	}
	
	public function beforeBuild()
	{
		wm()->add('customize.theme.selectScheme');
	}
}