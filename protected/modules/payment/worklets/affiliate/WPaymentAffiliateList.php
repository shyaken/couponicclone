<?php
class WPaymentAffiliateList extends UListWorklet
{
	public $modelClassName = 'MPaymentAffiliate';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Affiliate Codes');
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Name'), 'name' => 'name'),
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
		$link = url('/payment/affiliate/update');
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
		return array(CHtml::button($this->t('Add Affiliate Code'), array('id' => $id)));
	}
}