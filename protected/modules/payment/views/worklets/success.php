<form>
	<div class='row buttons'><?php
		app()->controller->widget('UJsButton', array(
			'label' => $this->t('Proceed'),
			'callback' => 'window.location = "'.$this->successUrl.'";',
		));
	?></div>
</form>
<?php
foreach($codes as $c)
	echo strtr($c->code, $data);