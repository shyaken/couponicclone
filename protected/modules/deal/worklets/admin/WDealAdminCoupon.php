<?php
class WDealAdminCoupon extends UListWorklet
{
	public $modelClassName = 'MDealCouponListModel';
	public $addMassButton = false;
	
	public function title()
	{
		return $this->t('Manage Coupons');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function columns()
	{
		$gridId = $this->getDOMId().'-grid';
		$markButtonImage = asma()->publish($this->module->basePath.DS.'images'.DS.'mark.png');
		$unmarkButtonImage = asma()->publish($this->module->basePath.DS.'images'.DS.'unmark.png');
		$buttonsTemplate = app()->user->checkAccess('administrator')
			? '{markButton} {unmarkButton} {delete}'
			: '{markButton} {unmarkButton}';
			
		$markButtonCfg = array(
			'label' => $this->t('Mark as Used'),
			'url' => 'url("/deal/admin/couponMark",array("id"=>$data->primaryKey))',
			'imageUrl' => $markButtonImage,
			'visible' => '$data->status==1',
		);
		
		if(app()->user->checkAccess('administrator'))
			$markButtonCfg['click'] = 'function(){
				$.fn.yiiGridView.update("'.$gridId.'", {
					type:"POST",
					url:$(this).attr("href"),
					success:function() {
						$.fn.yiiGridView.update("'.$gridId.'");
					}
				});
			return false;}';
		else
			$markButtonCfg['options'] = array('class'=>'uDialog');
			
		return array(
			array(
				'header' => $this->t('ID'),
				'name' => 'id',
				'value' => '"#".$data->couponId()',
			),
			array(
				'header' => $this->t('Order ID'),
				'name' => 'orderId',
			),
			array(
				'header' => $this->t('Deal ID'),
				'name' => 'dealId',
			),
			array(
				'header' => $this->t('Deal Name'),
				'name' => 'priceId',
				'value' => '$data->price?$data->price->name:""',
				'filter' => $this->priceFilter(),
			),
			array(
				'header' => $this->t('Redeem Location'),
				'name' => 'redeemLocationId',
				'value' => '$data->redeemLocation
					? wm()->get("location.helper")->locationAsText($data->redeemLocation->loc,$data->redeemLocation->address,$data->redeemLocation->zipCode," ")
					: ""',
				'type' => 'raw',
				'filter' => $this->redeemLocationFilter(),
			),
			array(
				'header' => $this->t('User'),
				'name' => 'userId',
				'value' => '$data->user?($data->user->email." [".$data->user->getName(true)."]"):""',
				'type' => 'raw',
			),
			array(
				'header' => $this->t('Status'),
				'name' => 'status',
				'filter' => array(1=>$this->t('Available'),2=>$this->t('Used'),3=>$this->t('Expired')),
				'value' => '$data->status==1?($data->deal->isExpired()?"'.$this->t('Expired').'":"'.$this->t('Available').'"):"'.$this->t('Used').'"',
			),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'template' => $buttonsTemplate,
				'deleteButtonUrl' => 'url("/deal/admin/couponDelete",array("id"=>$data->primaryKey))',
				'buttons' => array(
					'markButton' => $markButtonCfg,
					'unmarkButton' => array(
						'label' => $this->t('Un-mark as Used'),
						'url' => 'url("/deal/admin/couponUnmark",array("id"=>$data->primaryKey))',
						'imageUrl' => $unmarkButtonImage,
						'visible' => '$data->status==2',
						'click' => 'function(){
							$.fn.yiiGridView.update("'.$gridId.'", {
								type:"POST",
								url:$(this).attr("href"),
								success:function() {
									$.fn.yiiGridView.update("'.$gridId.'");
								}
							});
						return false;}',
					),
				),
			),
			
		);
	}
	
	public function buttons()
	{
		$buttons = array();
		if(app()->user->checkAccess('administrator'))
			$buttons[] = CHtml::ajaxSubmitButton($this->t('Delete'), url('/deal/admin/couponDelete'), array(
				'success' => 'function(){$.fn.yiiGridView.update("' .$this->getDOMId(). '-grid");}',
			));
		$buttons[] = CHtml::submitButton($this->t('Export to .CSV'), array(
			'id' => 'exportBut'
		));
		$buttons[] = CHtml::dropDownList('charset', 1, array(
			'utf-8'=>'utf-8',
			'windows-1251' => 'windows-1251',
			'iso-8859-2' => 'iso-8859-2 ',
			'iso-8859-5' => 'iso-8859-5',
			'macintosh' => 'macintosh',
			'windows-850' => 'windows-850',
			'windows-1250' => 'windows-1250',
			'euc-jp' => 'euc-jp',
			'iso-2022-jp' => 'iso-2022-jp',
			'shift_jis' => 'shift_jis',
			'windows-1252' => 'windows-1252'
		));
		return $buttons;
	}
	
	public function afterConfig()
	{
		if(!app()->user->checkAccess('administrator'))
			$this->model->companyId = MCompany::model()->find('userId=?',array(app()->user->id))->id;
		if($this->model->status === NULL)
			$this->model->status = 1;
	}
	
	public function taskRenderOutput()
	{
		cs()->registerScript(__CLASS__,'jQuery("#exportBut").click(function(){
			$(this).closest("form").attr({"action":"'.url('/deal/admin/export').'"});
		});');
		parent::taskRenderOutput();
	}

	public function afterBuild()
    {
		wm()->add('base.dialog');
	}
	
	public function taskRedeemLocationFilter()
	{
		if($this->model->dealId)
		{
			$locs = array();
			$models = MDealRedeemLocation::model()->findAll('dealId=?', $this->model->dealId);
			foreach($models as $m)
				$locs[$m->id] = wm()->get('location.helper')->locationAsText($m->loc,$m->address,$m->zipCode,' ');
			return $locs;
		}
		else
			return CHtml::textField('redeemFilter', '<- '.$this->t('Input Deal ID'), array('disabled' => 'disabled', 'style'=>'width: 90px'));
		
	}
	
	public function taskPriceFilter()
	{
		if($this->model->dealId)
		{
			$models = MDealPrice::model()->findAll('dealId=?', $this->model->dealId);
			$prices = array();
			foreach($models as $m)
				$prices[$m->id] = $m->name;
			return $prices;
		}
		else
			return CHtml::textField('priceFilter', '<- '.$this->t('Input Deal ID'), array('disabled' => 'disabled', 'style'=>'width: 90px'));
		
	}
}