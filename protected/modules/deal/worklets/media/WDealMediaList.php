<?php
class WDealMediaList extends UListWorklet
{
	public $modelClassName = 'MDealMedia';
	public $type = 'list';
	
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
	
	public function taskDeal()
	{
		static $deal;
		if(!isset($deal))
			$deal = isset($_GET['dealId'])?MDeal::model()->findByPk($_GET['dealId']):null;
		return $deal;
	}
	
	public function beforeConfig()
	{
		$_GET[$this->modelClassName]['dealId'] = $this->deal()->id;
		$this->options = array(
			'template' => "{summary}\n{sorter}\n<div class='clearfix'>{items}</div>\n{pager}",
		);
	}
	
	public function title()
	{
		$link = isset($_GET['dealId'])
			? url('/deal/admin/update',array('id'=>$_GET['dealId']))
			: url('/deal/admin/update');
		return CHtml::link($this->deal()->name,$link).': '.$this->t('Deal Media');
	}
	
	public function itemView()
	{
		return 'item';
	}
	
	public function taskBreadCrumbs()
	{
		$bC = array();
		if(app()->user->checkAccess('citymanager'))
			$bC[$this->t('Deals')] = url('/deal/admin/list');
		else
			$bC[$this->t('Company Admin')] = url('/company/admin');
		$bC[] = $this->deal()->name;
		$bC[] = $this->t('Deal Media');
		return $bC;
	}
	
	public function afterBuild()
	{
		wm()->get('base.init')->setState('admin',true);
		wm()->add('deal.media.update',null,array('deal' => $this->deal(),
			'position' => array('after' => $this->id)));
		wm()->add('deal.slideshow',null,array('deal' => $this->deal(), 'title' => $this->t('Preview'),
			'position' => array('after' => $this->id)));
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
	}
	
	public function taskRenderOutput()
	{
		parent::taskRenderOutput();
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().'-list .deleteLink, #'.$this->getDOMId().'-list .ajaxLink").live("click",function(){
			if($(this).hasClass("deleteLink"))
				if(!confirm("Are you sure you want to delete this item?"))
					return false;
			$.fn.yiiListView.update("'.$this->getDOMId().'-list", {
				type:"POST",
				url:$(this).attr("href"),
				success:function() {
					$.fn.yiiListView.update("'.$this->getDOMId().'-list");
					$("#wlt-DealSlideshow-1").uWorklet().load({url: "'.url('/deal/slideshow',array('dealId'=>$this->deal()->id)).'"});
					$("#wlt-DealMediaUpdate").uWorklet().load({url: "'.url('/deal/media/update',array('ajax' => 1, 'dealId'=>$this->deal()->id)).'"});
				}
			});
			return false;
		});');
	}
}