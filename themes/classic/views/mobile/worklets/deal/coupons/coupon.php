<?php
$status = wm()->get('deal.helper')->dealStatus($data->deal);
$active = $status == 'active';
$tippedOrClosed = $status == 'tipped' || $status == 'closed';
$coupons = $data->getAllCoupons(app()->user->id,var_export($this->model->hasUsed,true));
foreach($coupons as $c)
{
	$access = $tippedOrClosed && $c->order && $c->order->status == 2;
	if(!$access)
		continue;
		
	$url = url('/deal/print',array('id' => $c->id));
	$content = "<div class='column'>$data->name</div>";
	$content = ($data->deal->image
		? CHtml::image(app()->storage->bin($data->deal->imageBin)->getFileUrl('original'))
		: '') . $content;
	
	?><li class='clearfix'><?php
		echo $access ? CHtml::link($content,$url,array('class' => 'slide')) : $content;
	?></li><?php
}