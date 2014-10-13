<div class='recentDeal'>
	<div class='date'><?php echo app()->locale->dateFormatter->formatDateTime(
		utime($data->start,false),'medium',false); ?></div>
	<div class='content'>
		<div class='name'><?php
			echo CHtml::link($data->name,url('/deal/view',array('url'=>$data->url)));
		?></div>
		<div class='clearfix prepend-top'>
			<div class='column data'>
				<div class='stats txt-center'>
					<span class='num'><?php echo $data->stats && $data->stats->bought?$data->stats->bought:'0'; ?></span><br />
					<?php echo $this->t('Coupons Bought'); ?>
				</div>
				<div class='info prepend-top'>
					<div class='row price'>
						<label><?php echo $this->t('Price').':'; ?></label>
						<?php echo m('payment')->format($data->price); ?>
					</div>
					<div class='row'>
						<label><?php echo $this->t('Value').':'; ?></label>
						<?php echo m('payment')->format($data->value); ?>
					</div>
					<div class='row'>
						<label><?php echo $this->t('Savings').':'; ?></label>
						<?php echo m('payment')->format($data->value-$data->price); ?>
					</div>
				</div>
			</div>
			<div class='column last image'><?php
				if($data->imageBin)
					echo CHtml::link(
						CHtml::image(app()->storage->bin($data->imageBin)->getFileUrl('original')),
						url('/deal/view',array('url'=>$data->url))
					);
			?></div>
		</div>
	</div>
</div><?php
if($index && ($index+1)%2==0)
{
	?><div class='clearfix'></div><?php
}