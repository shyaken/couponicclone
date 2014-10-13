<?php
class WDealAdminPayment extends UListWorklet
{
	public $modelClassName = 'MDealPaymentModel';
	public $addCheckBoxColumn=false;
	public $addButtonColumn=false;
	public $addMassButton=false;
	
	public function title()
	{
		return $this->t('Manage Payments');
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
			array('header' => $this->t('Deal ID'), 'name' => 'id'),
			array('header' => $this->t('Funds Raised'), 'name' => 'funds', 'filter' => false,
				'value' => 'm("payment")->format($data->funds)'),
			array('header' => $this->t('Commission Held'), 'name' => 'funds', 'filter' => false,
				'value' => 'm("payment")->format($data->commissionHeld)'),
			array('header' => $this->t('Amount to Pay'), 'name' => 'funds', 'filter' => false,
				'value' => 'm("payment")->format($data->funds-$data->commissionHeld)'),
			array('header' => $this->t('Status'), 'name' => 'status',
				'value' => '$data->status==3?"'.$this->t('Paid').'":"'.$this->t("Not Paid").'"',
				'filter' => array(1 => 'Not Paid', 3 => 'Paid')),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'template' => '{payButton}<br />{markButton}',
				'buttons' => array(
					'payButton' => array(
						'label' => $this->t('Pay'),
						'url' => 'url("/deal/admin/pay",array("id"=>$data->primaryKey))',
						'options' => array('target' => '_blank'),
						'visible' => '$data->status<3',
					),
					'markButton' => array(
						'label' => $this->t('Mark as Paid'),
						'url' => 'url("/deal/admin/markPaid",array("id"=>$data->primaryKey))',
						'visible' => '$data->status<3',
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
}