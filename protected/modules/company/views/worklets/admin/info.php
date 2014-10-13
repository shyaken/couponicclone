<div>
	<div class='clearfix'>
		<h3 class='column'><?php echo $this->company()->name; ?></h3>
		<?php if(app()->user->checkAccess('company.edit',$this->company())) { ?>
		<div class='txt-right'><?php echo CHtml::link($this->t('Edit'),array('/company/admin/update')); ?></div>
		<?php } ?>
	</div>
	<?php if($this->company()->phone) { ?><p><?php
		echo $this->t('Phone').': '.$this->company()->phone; ?></p><?php } ?>
	<address><?php
		echo wm()->get('location.helper')->locationAsText(
			$this->company()->loc,
			$this->company()->address,
			$this->company()->zipCode
		);
	?></address>
	<?php echo CHtml::link($this->t('website'),$this->company()->website); ?>
</div>