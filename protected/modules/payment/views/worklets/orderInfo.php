<?php
	$info = array(
		$this->t('Order ID') => $this->model->id,
		$this->t('Payment Processor Order ID') => strpos($this->model->custom,':')
			? substr($this->model->custom,0,strpos($this->model->custom,':'))
			: $this->model->custom,
		$this->t('Date') => app()->getDateFormatter()->formatDateTime(utime($this->model->created), 'medium', 'short'),
		$this->t('Amount') => m('payment')->format($this->model->amount),
		$this->t('Contents') => wm()->get('payment.admin.list')->orderItems($this->model),
		$this->t('User') => $this->model->user->email.' ['.$this->model->user->name.']',
		$this->t('Current Status') => wm()->get('payment.admin.list')->status($this->model->status),
	);
?>
<div class='info append-bottom'>
	<?php foreach($info as $l=>$d) { ?>
	<div class='row'>
		<label><?php echo $l.':'; ?></label>
		<fieldset><?php echo $d; ?></fieldset>
	</div><?php
	} ?>
</div>