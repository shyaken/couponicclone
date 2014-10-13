<div class='box'>
	<ul class='horizontal'>
		<li><?php echo $this->t('Share this deal').':'; ?></li>
		<li>
			<?php echo CHtml::link('Facebook', 'http://www.facebook.com/share.php?u='.htmlspecialchars(wm()->get('deal.helper')->shareLink(array('url'=>$deal->url,'bypass'=>1))),
				array('target'=>'_blank', 'class' => 'facebookShare')); ?>
		</li>
		<li>
			<?php echo CHtml::link('Twitter', 'http://twitter.com/home?status='.rawurlencode($deal->name.'... ').wm()->get('deal.helper')->shareLink(array('url'=>$deal->url)),
				array('target'=>'_blank')); ?>
		</li>
		<li>
			<?php echo CHtml::mailto($this->t('Email a friend!'),
				'?body='.rawurlencode($deal->name.' '.wm()->get('deal.helper')->shareLink(array('url'=>$deal->url))).'&subject='.rawurlencode($deal->name)); ?>
		</li>
	</ul>
</div>
<h1><?php
	$title = '';
	if(wm()->get('deal.helper')->todays($deal))
		$title.= CHtml::link($this->t('Today\'s Deal:'), url('/deal/view',array('url'=>$deal->url))).' ';
	$title.= $deal->name;
	echo $title; ?></h1>
<div class='clearfix'>
	<div class="span-6">
		<div class='box txt-center'>
			<div class='price'><?php echo m('payment')->format($deal->price); ?></div><?php
			
			// Buy! button
			$message = $this->t('Buy!');
			$class = '';
			if($deal->purchaseMax && $deal->stats && $deal->stats->bought >= $deal->purchaseMax)
			{
				$message = $this->t('Sold Out!');
				$class = 'unavailable';
			}
			elseif(!wm()->get('deal.helper')->todays($deal))
			{
				$message = $this->t('Unavailable');
				$class = 'unavailable';
			}
			
			echo CHtml::button($message,array('class'=>'buyButton '.$class));
				
			?><hr />
			<table>
				<thead><tr>
					<th><?php echo $this->t('Value'); ?></th>
					<th><?php echo $this->t('Discount'); ?></th>
					<th><?php echo $this->t('Savings'); ?></th>
				</tr></thead>
				<tbody><tr>
					<td><?php echo m('payment')->format($deal->value); ?></td>
					<td><?php echo $deal->discount; ?>%</td>
					<td><?php echo m('payment')->format($deal->value-$deal->dealPrice); ?></td>
				</tr></tbody>
			</table>
		</div>
		<div class='box'><?php app()->controller->worklet('deal.timeLeft', array('start' => $deal->start, 'end' => $deal->end, 'timeZone' => $deal->timeZone)); ?></div>
		<div class='box'><?php app()->controller->worklet('deal.status', array('deal' => $deal)); ?></div>
	</div>
	<div class="span-11 last">
		<?php
		if($deal->imageBin)
		{
			app()->controller->worklet('deal.slideshow',array('deal' => $deal));
			//echo CHtml::image(app()->storage->bin($deal->imageBin)->getFileUrl('original'));
			?><hr /><?php
		}
		?>
		<div class='span-6'>
		<h3><?php echo $this->t('The Fine Print'); ?></h3>
		<?php echo app()->format->ntext($deal->finePrint); ?>
		</div>
		<div class='span-5 last'>
		<h3><?php echo $this->t('Highlights'); ?></h3>
		<ul>
		<?php
			$highlights = explode("\n",$deal->highlights);
			foreach($highlights as $h)
			{
				?><li><?php echo $h; ?></li><?php
			}
		?>
		</ul>
		</div>
	</div>
</div>