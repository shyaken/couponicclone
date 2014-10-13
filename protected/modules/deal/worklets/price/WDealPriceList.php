<?php
class WDealPriceList extends UListWorklet
{
	public $modelClassName = 'MDealPrice';
	
	public function title()
	{
		return $this->t('{title}: Price Options', array(
			'{title}' => $this->deal()->name
		));
	}
	
	public function taskDeal()
	{
		return MDeal::model()->findByPk($_GET['id']);
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
		if(isset($_GET['id']) && wm()->get('deal.edit.helper')->authorize($_GET['id']))
			return true;
		$this->accessDenied();
		return false;
	}
	
	public function afterConfig()
	{
		$this->model->dealId = $this->deal()->id;
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
		wm()->get('base.init')->setState('admin',true);
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Name'), 'name' => 'name'),
			array('header' => $this->t('Discount'), 'value' => '$data->discount."%"', 'filter' => false),
			array('header' => $this->t('Value'), 'name' => 'value', 'value' => 'm("payment")->format($data->value)'),
			array('header' => $this->t('Price'), 'name' => 'price', 'value' => 'm("payment")->format($data->price)'),
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
		$link = url('/deal/price/update', array('dealId' => $this->deal()->id));
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
		return array(CHtml::button($this->t('Add Price Option'), array('id' => $id)));
	}
}