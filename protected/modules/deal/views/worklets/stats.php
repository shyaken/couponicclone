<div class='info'>
	<div class='row'>
		<label><?php echo $this->t('Total money saved').':'; ?></label>
		<?php echo m('payment')->format($this->totals['savings']?$this->totals['savings']:'0'); ?>
	</div>
	<div class='row'>
		<label><?php echo $this->t('Total coupons bought').':'; ?></label>
		<?php echo $this->totals['coupons']?$this->totals['coupons']:'0'; ?>
	</div>
</div>