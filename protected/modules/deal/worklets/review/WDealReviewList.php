<?php
class WDealReviewList extends UListWorklet
{
	public $modelClassName = 'MDealReview';	
	
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
	
	public function taskDeal()
	{
		static $deal;
		if(!isset($deal))
			$deal = isset($_GET['id'])?MDeal::model()->findByPk($_GET['id']):null;
		return $deal;
	}
	
	public function beforeConfig()
	{
		$_GET[$this->modelClassName]['dealId'] = $this->deal()->id;
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
	}
	
	public function title()
	{
		$link = isset($_GET['id'])
			? url('/deal/admin/update',array('id'=>$_GET['id']))
			: url('/deal/admin/update');
		return CHtml::link($this->deal()->name,$link).': '.$this->t('Deal Reviews');
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Name'), 'name' => 'name'),
			array('header' => $this->t('Website'),'name' => 'website', 'type' => 'url'),
			array(
				'name' => 'review',
				'header' => $this->t('Review'),
				'value' => 'substr($data->review,0,100)."..."',
			),
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
		$link = isset($_GET['id'])
			? url('/deal/review/update',array('dealId'=>$_GET['id']))
			: url('/deal/review/update');
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
		return array(CHtml::button($this->t('Add Deal Review'), array('id' => $id)));
	}
	
	public function taskGetButtons()
	{
		$buttons = array(
			CHtml::ajaxSubmitButton($this->t('Delete'), url('/deal/review/delete'), array(
				'success' => 'function(){$.fn.yiiGridView.update("' .$this->getDOMId(). '-grid");}',
			))
		);
		return CMap::mergeArray($buttons,$this->buttons());
	}
	
	public function taskBreadCrumbs()
	{
		$bC = array();
		if(app()->user->checkAccess('citymanager'))
			$bC[$this->t('Deals')] = url('/deal/admin/list');
		else
			$bC[$this->t('Company Admin')] = url('/company/admin');
		$bC[] = $this->deal()->name;		
		$bC[] = $this->t('Deal Reviews');
		return $bC;
	}
	
	public function afterBuild()
	{
		wm()->get('base.init')->setState('admin',true);
		cs()->registerScript(__CLASS__.'#'.$this->id,
			'$("#'.$this->getDOMId().'-grid a.delete").live("click",function(){
				$("#wlt-DealReviewUpdate").closest(".worklet-pushed-content").remove();
			});');
	}
}