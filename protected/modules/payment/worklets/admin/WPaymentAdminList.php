<?php
class WPaymentAdminList extends UListWorklet
{
	public $modelClassName = 'MPaymentOrderListModel';
	public $addCheckBoxColumn=false;
	public $addMassButton=false;
	
	public function title()
	{
		return $this->t('Manage Orders');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function columns()
	{
		$gridId = $this->getDOMId().'-grid';
		return array(
			array(
				'header' => $this->t('ID'),
				'name' => 'id',
			),
			array(
				'header' => $this->t('Processor Order ID'),
				'name' => 'custom',
				'value' => 'strpos($data->custom,":")?substr($data->custom,0,strpos($data->custom,":")):$data->custom',
			),
			array('header' => $this->t('Date'), 'name' => 'created',
				'value' => 'app()->getDateFormatter()->formatDateTime($data->created, "medium", false)'),
			array(
				'header' => $this->t('Amount'),
				'name' => 'amount',
				'value' => 'm("payment")->format($data->amount)'
			),
			array(
				'header' => $this->t('Contents'),
				'name' => 'itemsType',
				'filter' => $this->itemsFilter(),
				'value' => 'wm()->get("payment.admin.list")->orderItems($data)',
				'type' => 'raw',
			),
			array(
				'header' => $this->t('User'),
				'name' => 'userId',
				'value' => '$data->user
					? $data->user->email."<br />[".$data->user->getName(true)."]"
					: wm()->get("payment.admin.list")->t("User unknown (deleted)")',
				'type' => 'raw',
			),
			array(
				'header' => $this->t('Status'),
				'name' => 'status',
				'filter' => array(
					0 => $this->t('Placed'),
					1 => $this->t('Authorized'),
					2 => $this->t('Paid'),
					3 => $this->t('Voided/Refunded'),
				),
				'value' => 'wm()->get("payment.admin.list")->status($data->status)',
			),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'template' => '{view}',
				'viewButtonUrl' => 'url("/payment/admin/view",array("id"=>$data->primaryKey))',
			),
		);
	}
	
	public function beforeConfig()
	{
		if(!isset($_GET[$this->modelClassName.'_sort']))
			$_GET[$this->modelClassName.'_sort'] = 'created.desc';
	}
	
	public function taskStatus($id)
	{
		$s = array(
			0 => $this->t('Placed'),
			1 => $this->t('Authorized'),
			2 => $this->t('Paid'),
			3 => $this->t('Voided/Refunded'),
		);
		return isset($s[$id])?$s[$id]:null;
	}
	
	public function taskOrderItems($data)
	{
		$d = '';
		foreach($data->items as $item)
		{
			$w = wm()->get($item->itemModule.'.order');
			if($w)
				$d.= CHtml::tag('div',array(),$w->description($item));
		}
		return $d;
	}
	
	public function taskItemsFilter()
	{
		return array(
			'payment.0' => $this->t('Credits'),
		);
	}
	
	public function beforeBuild()
	{
		wm()->add('payment.affiliate.list');
		wm()->add('payment.credit.list');
	}
}