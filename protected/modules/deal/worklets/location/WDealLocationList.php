<?php
class WDealLocationList extends UListWorklet
{
	public $modelClassName = 'MDealLocation';
	
	public function title()
	{
		return $this->t('{title}: Publish Locations', array(
			'{title}' => $this->deal()->name
		));
	}
	
	public function taskDeal()
	{
		return MDeal::model()->findByPk($_GET['dealId']);
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeAccess()
	{
		if(isset($_GET['dealId']) && wm()->get('deal.edit.helper')->authorize($_GET['dealId']))
			return true;
		$this->accessDenied();
		return false;
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Location'), 'value' => 'wm()->get("deal.location.list")->loc($data)'),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'template' => '{delete}',
				'deleteButtonUrl' => 'url("'.$this->getParentPath().'/delete",array("id"=>$data->id))'
			),
		);
	}
	
	public function filter()
	{
		return null;
	}
	
	public function buttons()
	{
		$link = url('/deal/location/update', array('dealId' => $_GET['dealId']));
		
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
		$addButton = CHtml::button($this->t('Add New Location'), array('id' => $id));
		
		$list = wm()->get('deal.location.list');
		$id = $this->getDOMId().CHtml::ID_PREFIX.CHtml::$count++;
		cs()->registerScript($this->getId().$id,'jQuery("#' .$id. '").click(function(e){
		e.preventDefault();
		$.fn.yiiGridView.update("'.$list->getDOMId().'-grid", {
			type:"POST",
			url:"'.url('/deal/location/all',array('dealId'=>$_GET['dealId'])).'",
			success:function() {
				$.fn.yiiGridView.update("'.$list->getDOMId().'-grid");
			}
		});
		});');
		$allButton = CHtml::button($this->t('Publish to All Locations'), array('id' => $id));
		
		return array($addButton,$allButton);
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('Locations');
		return $r;
	}
	
	public function afterConfig()
	{
		$this->model->dealId = $_GET['dealId'];
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
		wm()->add('deal.edit.redeemSelect',null,array('position' => array('after' => $this->id)));
		wm()->add('deal.location.redeemList',null,array('position' => array('after' => $this->id)));
	}
	
	public function taskLoc($data)
	{
		if($data->loc)
			return wm()->get("location.helper")->locationAsText($data->loc,false,false,"\n");
		else
			return $this->t('All Locations');
	}
}