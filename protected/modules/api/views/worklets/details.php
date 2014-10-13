<?php 
	foreach($data as $k=>$v){
		if(is_array($v)){
			if (!is_int($k)){
				?><<?php echo $k; ?>><?php 
			}
			$this->render('details',array('data'=>$v));
			if (!is_int($k)){
				?></<?php echo $k; ?>><?php
			}
		}
		else {
			?><<?php echo $k; ?>><?php echo htmlspecialchars($v);?></<?php echo $k; ?>><?php 
		}
	} 
	
?>
