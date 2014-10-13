<?php
class WDealCoupons extends UListWorklet
{
	public $modelClassName = 'MDealPaymentOrderModel';
	public $space = 'inside';
	
	public function accessRules()
	{
		return array(
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function form()
	{
		return false;
	}
	
	public function beforeBuild()
	{
		if(wm()->get('base.helper')->isMobile())
			$_GET['type'] = 'available';
			
		if(app()->controller->routeEased == 'payment/success')
			wm()->get('payment.cart')->empty();
			
		if(!app()->request->isAjaxRequest && !wm()->get('base.helper')->isMobile())
		{
			$w = wm()->addCurrent('user.accountTabs');
			$w->select = 0;
			$this->show = false;
			return false;
		}
		elseif(!isset($_GET['type']))
		{
			$w = wm()->add('deal.couponsTabs');
			$w->select = 0;
			$this->show = false;
			return false;
		}
	}
	
	public function title()
	{
		return $this->t('My Coupons');
	}
	
	public function columns()
	{
		return array(
			array(
				'header' => '',
				'type' => 'image',
				'value' => '$data->deal->imageBin
					? (app()->storage->bin($data->deal->imageBin)->getFileUrl("original_t")
						? app()->storage->bin($data->deal->imageBin)->getFileUrl("original_t")
						: app()->storage->bin($data->deal->imageBin)->getFileUrl("original"))
					: null',
			),
			array(
				'header' => $this->t('Name'),
				'name' => 'deal.name',
				'value' => 'wm()->get("'.$this->getId().'")->render("coupons",array(
					"deal"=>$data->deal,
					"coupons"=>$data->getAllCoupons(app()->user->id,'.var_export($this->model->hasUsed,true).')))',
			),
			array(
				'name' => $this->t('Purchase Date'),
				'value' => 'app()->getDateFormatter()->formatDateTime(
					utime($data->getOrderDate($data->getAllCoupons(app()->user->id,'
					. var_export($this->model->hasUsed,true).'),false)), "medium", false)',
			),
			array(
				'name' => $this->t('Expiration Date'),
				'value' => '$data->deal->expire
					? app()->getDateFormatter()->formatDateTime(
						utime($data->deal->expire,false), "medium", false)
					: "'.$this->t('Never').'"',
			),
		);
	}
	
	public function filter()
	{
		return null;
	}
	
	public function beforeGetColumns()
	{
		return $this->columns();
	}
	
	public function beforeGetButtons()
	{
		return array();
	}
	
	public function itemView()
	{
		return 'coupon';
	}
	
	public function afterConfig()
	{
		if($this->model)
		{
			$this->model->userId = app()->user->id;
			$this->model->status = '>0';
			switch($_GET['type'])
			{
				case 'available':
					$this->model->hasUsed = false;
					$this->model->expired = false;
					break;
				case 'used':
					$this->model->hasUsed = true;
					break;
				case 'expired':
					$this->model->hasUsed = false;
					$this->model->expired = true;
					break;
			}
		}
		
		if(wm()->get('base.helper')->isMobile())
		{
			$this->space = 'content';
			$this->type = 'list';
		}
		else
		{
			$this->options['enableSorting'] = false;
			
			$gridId = $this->getDOMId().'-grid';
			cs()->registerScript(__CLASS__.'#'.$this->id,'jQuery("#'.$gridId.' a.mark").live("click",function(){
				$.fn.yiiGridView.update("'.$gridId.'", {
					type:"POST",
					url:$(this).attr("href"),
					success:function() {
						$.fn.yiiGridView.update("'.$gridId.'");
					}
				});
				return false;			
			});');
		}
	}
}