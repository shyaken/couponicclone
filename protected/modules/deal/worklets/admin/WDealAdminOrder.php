<?php
class WDealAdminOrder extends UListWorklet
{
	public $modelClassName = 'MDealOrderList';
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
				'header' => $this->t('Deal ID'),
				'name' => 'deal',
				'value' => '$data->deals[0]->dealId',
			),
			array(
				'header' => $this->t('User'),
				'name' => 'userId',
				'value' => '$data->user
					? $data->user->email."<br />[".$data->user->getName(true)."]"
					: wm()->get("deal.admin.order")->t("User unknown (deleted)")',
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
}