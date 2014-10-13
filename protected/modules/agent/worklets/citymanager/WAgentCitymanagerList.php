<?php
class WAgentCitymanagerList extends UListWorklet
{
	public $modelClassName = 'MCitymanagerList';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Manage City Managers');
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Name'),'name' => 'lastName',
				'value' => '$data->name'),
			array('header' => $this->t('Email'),'name' => 'email'),
			array('header' => $this->t('Cities'), 'type' => 'ntext', 'name' => 'city', 'value' => 'wm()->get("agent.citymanager.list")->cities($data)'),
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
	
	public function taskCities($data)
	{
		$w = wm()->get('location.helper');
		$ret = '';
		foreach($data->locs as $loc)
			if($loc->location == 0)
				$ret.= $this->t('All Locations')."\n";
			else
				$ret.= $w->locationAsText($loc->loc,false,false,' ')."\n";
		return $ret;
	}
	
	public function buttons()
	{
		$link = url('/agent/citymanager/create');
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
		return array(CHtml::button($this->t('Add City Manager'), array('id' => $id)));
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Agents') => url('/agent'),
			$this->t('Manage City Managers') => url('/agent/citymanager/list'),
		);
	}
}