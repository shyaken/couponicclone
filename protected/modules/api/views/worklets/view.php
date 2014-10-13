<?php echo '<?xml version="1.0" encoding="UTF-8" ?>';?>
<couponic>
	<status><?php echo htmlspecialchars($status) ?></status>
    <errorCode><?php echo htmlspecialchars($errorCode) ?></errorCode>
	<errorMessage><?php echo htmlspecialchars($errorMessage) ?></errorMessage>
	<?php $this->render('details',array('data' => $data)); ?>
</couponic>