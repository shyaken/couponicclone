<div class='column mediaItem'><?php
	echo CHtml::tag('div', array('class' => 'description', 'id'=>'MediaDescription_'.$data->id), $data->description);
	if($data->type == 1)
	{
		echo CHtml::image(app()->storage->bin($data->deal->image)->getFileUrl($data->data));
		echo $data->data == 'original'
			? $this->t('Main Image')
			: CHtml::link($this->t('Mark as Main'), url('/deal/media/main', array('id' => $data->id)), array(
				'class' => 'ajaxLink'));
	}
	elseif($data->type == 2)
	{
		$video = preg_replace('/width=(["|\'|0-9]+)/','width="225"',$data->data);
		$video = preg_replace('/height=(["|\'|0-9]+)/','height="180"',$video);
		echo $video;
		echo CHtml::tag('div', array('id'=>'MediaDescription_'.$data->id , 'class'=>'MediaDescription'), $data->description);
		echo $this->t('Embed Code');
	}
	echo CHtml::link($this->t('Move Up'), url('/deal/media/position', array('id' => $data->id, 'dir' => 'up')), array(
		'class' => 'ajaxLink'
	));
	echo CHtml::link($this->t('Move Down'), url('/deal/media/position', array('id' => $data->id, 'dir' => 'down')), array(
		'class' => 'ajaxLink'
	));
	echo CHtml::link($this->t('Delete'), url('/deal/media/delete', array('id' => $data->id)), array(
		'class' => 'deleteLink'
	));
        echo CHtml::link($this->t('Description'), url('/deal/media/description', array('id' => $data->id, 'class' => 'uDialog')), array(
		'class' => 'uDialog'
	));
?></div>