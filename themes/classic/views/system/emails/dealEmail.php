<?php 
$mailer->Subject = $deal->name;
$h = wm()->get('customize.theme.helper');
?>
<div style='font-weight: bold; font-size: 18pt; padding: 0 0 10px 0'><?php echo CHtml::link($deal->name,aUrl('/deal/view',array('url' => $deal->url),'http')); ?></div>
<div style='overflow: hidden; zoom: 1'>
	<div style='width:400px; float:right;'>
		<?php echo $deal->image
				? CHtml::image(app()->storage->bin($deal->image)->getFileUrl('original','http'), $deal->name, array('style'=>'width:400px'))
				: ''; ?>
	</div>
	<div style='margin-right: 410px;'>
		<div style='background: <?php echo $h->color('deal.info.box1.header.bcg'); ?>; overflow: hidden; zoom: 1;'>
			<div style='padding: 5px 5px 5px 0; float: right'>
				<a href='<?php echo aUrl('/deal/view',array('url' => $deal->url),'http'); ?>' style='
					display: block;
					background-image: url(<?php echo app()->request->hostInfo.app()->theme->baseUrl; ?>/images/buyButtonEmailBcg.png);
					background-color: <?php echo $h->color('buy.button.bcg'); ?>;
					background-position: top left;
					background-repeat: no-repeat;
					width: 119px; height: 40px; line-height: 40px;
					color: #fff; font-weight: bold; text-align: center;
				'><?php echo $this->t('See Today\'s Deal'); ?></a>
			</div>
			<div style='font-size: 18pt; color: #fff;
					text-align: center; line-height: 50px;'><?php echo m('payment')->format($deal->price); ?></div>
		</div>
		<div style='background: <?php echo $h->color('deal.info.box1.content.bcg'); ?>; border: 1px solid <?php echo $h->color('deal.info.box1.content.border'); ?>; border-top: none;'>
			<table style='text-align: center;border-collapse: collapse;width: 100%;'>
				<thead><tr>
					<th style='font-weight: normal'><?php echo $this->t('Value'); ?></th>
					<th style='font-weight: normal'><?php echo $this->t('Discount'); ?></th>
					<th style='font-weight: normal'><?php echo $this->t('Savings'); ?></th>
				</tr></thead>
				<tbody style='font-size: 150%;'><tr>
					<td><?php echo m('payment')->format($deal->value); ?></td>
					<td><?php echo round((($deal->value-$deal->price)/$deal->value)*100); ?>%</td>
					<td><?php echo m('payment')->format($deal->value-$deal->price); ?></td>
				</tr></tbody>
			</table>
		</div>
		<div style='padding-top:10px;'>
			<div style='padding: 10px; border: 1px solid <?php echo $h->color('deal.info.box2.border'); ?>'>
				<strong><?php echo $this->t('Company Information').':'; ?></strong>
				<p><strong><?php echo $deal->company->name; ?></strong><br />
				<?php echo CHtml::link($this->t('website'),$deal->company->website); ?></p>
				<p><strong><?php echo $this->t('Location').':'; ?></strong><br />
				<?php
				echo wm()->get('location.helper')->locationAsText(
					$deal->company->loc,
					$deal->company->address,
					$deal->company->zipCode
				);
				?></p>
			</div>
		</div>
	</div>
</div>
<div style='padding-top: 10px; overflow: hidden; zoom: 1'><?php
	if(count($side))
	{
		?><div style='float: right; width: 200px; border: 2px solid <?php echo $h->color('deal.info.box2.border'); ?>; padding: 10px;'>
			<div style='background: <?php echo $h->color('deal.info.box2.bcg'); ?>; padding: 3px; color: <?php echo $h->color('deal.info.box2.border'); ?>; font-weight: bold;'><?php echo $this->t('More Great Deals'); ?></div><?php
			$i = 0;
			foreach($side as $sdeal)
			{
				$i++;
				$style = $i == count($side) ? '' : 'border-bottom: 1px solid '.$h->color('deal.info.box2.bcg');
					
				?><div style='<?php echo $style; ?>'><?php
					echo CHtml::link($sdeal->name,aUrl('/deal/view',array('url' => $sdeal->url),'http'));
				?></div><?php
			}
		?></div><?php
	}
	?><div><?php echo $deal->description; ?></div>
</div>