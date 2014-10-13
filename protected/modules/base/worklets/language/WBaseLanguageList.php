<?php
class WBaseLanguageList extends UListWorklet
{
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Supported Languages');
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Language'), 'name' => 'name'),
			array('header' => $this->t('Code'), 'name' => 'code'),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'template' => '{update} {delete}',
				'updateButtonUrl' => 'url("'.$this->getParentPath().'/update",array("id"=>$data["code"]))',
				'deleteButtonUrl' => 'url("'.$this->getParentPath().'/delete",array("id"=>$data["code"]))',
				'buttons' => array(
					'update' => array('click' => 'function(){
						$("#' .$this->getDOMId(). '").uWorklet().load({position:"appendReplace",url: $(this).attr("href")});
						return false;}'),
				),
			),
		);
	}
	
	public function buttons()
	{
		$link = url('/base/language/update');
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
		return array(CHtml::button($this->t('Add New Language'), array('id' => $id)));
	}
	
	public function dataProvider()
	{
		$data = array();
		$languages = $this->param('languages');
		if(is_array($languages))
			foreach($languages as $k=>$v)
			{
				$item = array(
					'id' => $k,
					'code' => $k,
					'name' => $v
				);
				$data[] = $item;
			}
		
		return new CArrayDataProvider($data);
	}
}