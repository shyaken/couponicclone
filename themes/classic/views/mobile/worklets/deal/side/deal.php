<?php $url = url('/deal/view',array('url'=>$data->url)); ?>
<li class='sideDeal clearfix'>
<?php
if($data->image)
	echo CHtml::link(CHtml::image(app()->storage->bin($data->imageBin)->getFileUrl('original')),$url);
?><div class='column'><?php echo CHtml::link($data->name,$url); ?></div>
</li>