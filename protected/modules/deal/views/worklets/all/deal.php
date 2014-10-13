<div class='allDeal'>
	<div class='image'><?php
		if($data->imageBin)
			echo CHtml::link(
				CHtml::image(app()->storage->bin($data->imageBin)->getFileUrl('original')),
				url('/deal/view',array('url'=>$data->url))
			);
	?></div>
	<div class='name'><?php
		echo CHtml::link($data->name,url('/deal/view',array('url'=>$data->url)));
	?></div>
	<div class='clearfix'>
		<div class='column viewLink'><?php echo CHtml::link($this->t('View this deal'),url('/deal/view',array('url'=>$data->url))); ?></div><?php
		if($data->start < time()) {
			?><div class='txt-right date'><?php echo $this->t('{time} left', array(
				'{time}' => CHtml::tag('span', array('id' => 'timer_'.$data->id), ' ')
			)); ?></div><?php
		} else {
			?><div class='txt-right date'><?php echo app()->locale->dateFormatter->formatDateTime(
			utime($data->start,false),'medium',false); ?></div><?php
		}
	?></div>
	<div class="afterUpdTimer" style="display: none"><?php echo $data->id; ?>|<?php echo $data->end; ?>|<?php echo $data->end-time() > 86400 ? '{dn} {dl} {hn}:{mn}:{sn}' : '{hn}:{mn}:{sn}';?></div>
</div><?php
if($index && ($index+1)%2==0)
{
	?><div class='clearfix'></div><?php
}