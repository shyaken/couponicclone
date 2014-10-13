<form action='<?php echo url('/payment/credits'); ?>' method='post'>
	<div class='row buttons'><?php
		echo CHtml::submitButton($this->t('Add Credits'));
	?></div>
</form>