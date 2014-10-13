<?php
class WPaymentAdminPaymentHistory extends UListWorklet {
	
	public $modelClassName = 'MTransactionHistory';
	public $addCheckBoxColumn=false;
	public $addMassButton=false;
	
	public $addButtonColumn=false;
	
	public function title()
	{
		return $this->t('Transaction History');
	}
	
	public function afterConfig() {
		$this->model->userId = $_GET['id'];
	}
	
	
	public function accessRules()
	{
		return array(
		array('allow', 'roles' => array('administrator')),
		array('deny', 'users'=>array('*'))
	);
	}
	
	public function columns() {
		$gridId = $this->getDOMId().'-grid';
		return array(
			array(
				'header' => $this->t('Date'),
				'name' => 'date',
				'value' => 'app()->getDateFormatter()->formatDateTime($data->date, "medium", false)'  
			),
			array(
				'header' => $this->t('Amount'),
				'name' => 'amount',
				'value' => 'm("payment")->format($data->amount)'
			),
			array(
				'header' => $this->t('Comment'),
				'name' => 'comment',
				'type' => 'ntext',
			),
		);
	}
}