<?php
class WLocationAdminList extends UListWorklet
{
	public $modelClassName = 'MLocationPreset';
	public $addCheckBoxColumn=false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Manage Locations');
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Location'), 'value' => 'wm()->get("location.helper")->locationAsText($data->loc,false,false," ")'),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'buttons' => array(
					'update' => array('click' => 'function(){
						$("#' .$this->getDOMId(). '").uWorklet().load({position:"appendReplace",url: $(this).attr("href")});
						return false;}'),					
				),
			),
		);
	}
	
	public function filter()
	{
		return null;
	}
	
	public function buttons()
	{
		$link = url('/location/admin/update');
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
		return array(CHtml::button($this->t('Add New Location'), array('id' => $id)));
	}
	
	public function beforeBuild()
	{
		wm()->add('base.dialog');
		wm()->add('admin.setupMenu');
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Setup') => url('/admin/setup'),
			$this->title() => url('/location/admin/list')
		);
	}
}