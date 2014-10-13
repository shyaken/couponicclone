<div class='sideDeal clearfix'><div class='column <?php echo ($data->imageBin) ? '' : 'last'; ?>'><?php
	if($data->imageBin){
		$fileUrl = app()->storage->bin($data->imageBin)->getFileUrl('original_t');
		$fileUrl = $fileUrl?$fileUrl:app()->storage->bin($data->imageBin)->getFileUrl('original');
		echo CHtml::link(
			CHtml::image($fileUrl,$data->name),
					url('/deal/view',array('url'=>$data->url))
		);
	}
	else
		echo CHtml::link($this->t('View'),url('/deal/view',array('url'=>$data->url)), array(
			'class' => 'viewLink'
		));
	?></div><?php	
	echo CHtml::link($data->name,url('/deal/view',array('url'=>$data->url)));
?></div>