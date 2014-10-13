<?php
class WPaymentCreditUpdate extends UFormWorklet
{	
	public $modelClassName = 'MTransactionHistory';
	public $space = 'inside';
	
	public function title()
	{
		return $this->t('Edit User Credit');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	
	public function properties()
	{			
		if(!$this->model->action)
			$this->model->action = 'plus';
		return array(
			'elements' => array(
				'action' => array('type' => 'radiolist', 'label' => $this->t('Action'),
					'items' => array(
						'plus' => $this->t('Add'),
						'minus' => $this->t('Deduct'),
					), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"
				),
				'amount' => array(
					'type' => 'text',
					'hint' => m('payment')->param('cSymbol'),
					'class' => 'short',
					'layout' => "{label}\n<span class='hintInlineBefore'>{hint}\n{input}</span>"
				),
				'comment' => array(
					'type' => 'textarea',
				),
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->t('Update')),
			),
			'model' => $this->model
		);
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
        public function beforeSave() {
            $this->model->userId = $_GET['id'];
            $this->model->date = time();
            if($this->model->action == 'minus') {
                $this->model->amount *= -1;
            }
        }
        
        public function taskSave() {
            parent::taskSave();
            wm()->get('payment.helper')->addCredit($this->model->amount, MUser::model()->findByPk($_GET['id']));
        }
        
        public function afterBuild()
        {
            wm()->add('payment.admin.paymentHistory');
        }

        public function ajaxSuccess()
	{
		$listWorklet = wm()->get('payment.credit.list');
                $listWorklet2 = wm()->get('payment.admin.paymentHistory');
		$message = $this->t('User credit has been successfully updated.');
		$json = array(
			'info' => array(
				'replace' => $message,
				'fade' => 'target',
				'focus' => false,
			),
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$listWorklet->getDOMId(). '-grid");
                                    $.fn.yiiGridView.update("' .$listWorklet2->getDOMId(). '-grid");
                                    $(this).closest(".worklet-pushed-content").remove()')
			),
		);
			
		wm()->get('base.init')->addToJson($json);
	}
}