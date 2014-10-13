<h1><?php
	$title = '';
	if(wm()->get('deal.helper')->todays($deal))
		$title.= CHtml::link($this->t('Today\'s Deal:'), url('/deal/view',array('url'=>$deal->url))).' ';
	$title.= $deal->name;
	echo $title; ?></h1>
<div class='clearfix'>
	<div class="span-6">
		<div class='blueBox'>
			<div class='header clearfix'><?php
			
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
				
				?><div class='buyButton <?php echo $class; ?>'><?php
					echo $message;
				?></div>
				<div class='price'><?php echo m('payment')->format($deal->price); ?></div>
			</div>
			<div class='content'>
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
		</div>		
		<div class='greenBox'><?php app()->controller->worklet('deal.timeLeft', array('start' => $deal->start, 'end' => $deal->end, 'timeZone' => $deal->timeZone)); ?></div>
		<div class='greenBox'><?php app()->controller->worklet('deal.status', array('deal' => $deal)); ?></div>
	</div>
	<div class="span-11 last">
		<?php
		if($deal->imageBin)
		{
			app()->controller->worklet('deal.slideshow',array('deal' => $deal));
			?><hr class='space' /><?php
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
		<hr />
		<ul class='horizontal clearfix shareMenu'>
			<li class='h'><?php echo $this->t('Share').':'; ?></li>
			<li><?php echo CHtml::mailto(
				CHtml::image(app()->theme->baseUrl.'/images/email.png','Email'),
				'?body='.rawurlencode($deal->name.' '.wm()->get('deal.helper')->shareLink(array('url'=>$deal->url))).'&subject='.rawurlencode($deal->name)); ?></li>
			<!-- Twitter -->
			<li><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo wm()->get('deal.helper')->shareLink(array('url'=>$deal->url)); ?>" data-text="<?php echo str_replace('"','\"',$deal->name); ?>" data-count="none" data-lang="<?php echo app()->language; ?>">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></li>
			<!-- Google+ -->
			<li>
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
				<g:plusone size="medium" width="55" href="<?php echo wm()->get('deal.helper')->shareLink(array('url'=>$deal->url,'bypass'=>1)); ?>"></g:plusone>
			</li>
			<!-- Facebook -->
			<li><?php
				if(!m('facebook') && !m('social'))
				{
					?><div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><?php
				}
			?><fb:like show_faces="false" layout="button_count" send="false" href="<?php echo wm()->get('deal.helper')->shareLink(array('url'=>$deal->url,'bypass'=>1)); ?>"></fb:like></li>
		</ul>
	</div>
</div>