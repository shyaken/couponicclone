<?php
echo CHtml::link($deal->name,url('/deal/view',array('url'=>$deal->url)));
$status = wm()->get('deal.helper')->dealStatus($deal);
$active = $status == 'active';
$tippedOrClosed = $status == 'tipped' || $status == 'closed' || $status == 'paid';
if($active)
{
	$left = $deal->stats?$deal->purchaseMin-$deal->stats->bought:$deal->purchaseMin;
	?><p><?php echo $this->t('This deal requires <strong>{num} more people</strong> until we get it.', array(
		'{num}' => $left
	)); ?></p><?php
}
?>
<table>
<?php
foreach($coupons as $c)
{
	$access = $tippedOrClosed && $c->order && $c->order->status == 2;
?>
	<tr>
		<td style="width:100px"><strong>#<?php echo $active?'****'.substr($c->couponId(),-4):$c->couponId(); ?></strong></td>
		<td><?php echo $access && !$deal->isExpired()
			? CHtml::link($this->t('Print'),url('/deal/print',array('id'=>$c->id)),array('target'=>'_blank'))
			: '&nbsp;' ?></td>
		<td><?php echo $access && $c->status < 2 && $c->userStatus < 2
			? CHtml::link($this->t('Mark as used'),url('/deal/mark',array('id'=>$c->id)),array('class' => 'mark'))
			: '&nbsp;' ?></td>
	</tr>
<?php
}
?>
</table>