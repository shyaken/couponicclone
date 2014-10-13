<?php
class WDealLocationRedeemList extends UListWorklet
{
	public $modelClassName = 'MDealRedeemLocation';
	public $addMassButton = false;
	
	public function title()
	{
		return $this->t('{title}: Redeem Locations', array(
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
	
	public function filter()
	{
		return null;
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
			array('header' => $this->t('Location'), 'value' => 'wm()->get("deal.location.redeemList")->loc($data)'),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'deleteButtonUrl' => 'url("'.$this->getParentPath().'/redeemDelete",array("id"=>$data->id))',
				'updateButtonUrl' => 'url("'.$this->getParentPath().'/redeemUpdate",array("id"=>$data->id))',
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
		$link = url('/deal/location/redeemUpdate', array('dealId' => $_GET['dealId']));
		
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
		
		return array(CHtml::ajaxSubmitButton($this->t('Delete'), url($this->getParentPath().'/redeemDelete'), array(
			'success' => 'function(){$.fn.yiiGridView.update("' .$this->getDOMId(). '-grid");}',
		)),$addButton);
	}
	
	public function afterConfig()
	{
		$this->model->dealId = $_GET['dealId'];
	}
	
	public function taskLoc($data)
	{
		return wm()->get("location.helper")->locationAsText($data->loc,$data->address,$data->zipCode, "\n");
	}
}