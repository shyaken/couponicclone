<?php
class WUserProfileList extends UListWorklet
{
	public $modelClassName = 'MUserProfileSetting';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Custom Profile Fields');
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Label'),'name' => 'label'),
			array('header' => $this->t('Type'), 'name' => 'type', 'value' => 'wm()->get("user.profile.helper")->type($data->type)'),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'buttons' => array(
					'update' => array('click' => 'function(){
						$("#' .$this->getDOMId(). '").uWorklet().load({position:"appendReplace",url: $(this).attr("href")});
						return false;}'),					
				),
			)
		);
	}
	
	public function buttons()
	{
		$link = url('/user/profile/update');
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
		return array(CHtml::button($this->t('Add Profile Field'), array('id' => $id)));
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Users') => url('/user/admin/list'),
			$this->t('Custom Profile Fields') => url('/user/profile/list'),
		);
	}
	
	public function beforeBuild()
	{
		wm()->add('user.admin.menu');
	}
}