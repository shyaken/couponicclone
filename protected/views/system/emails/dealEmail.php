<?php $mailer->Subject = $deal->name; ?>
<div style='width:660px;'>
	<div style='font-weight: bold; font-size: 18pt; padding: 0 0 10px 0'><?php echo CHtml::link($deal->name,aUrl('/deal/view',array('url' => $deal->url),'http')); ?></div>
	<div style='overflow: hidden; zoom: 1'>
		<div style='width:400px; float:right;'>
			<?php echo $deal->image
				? CHtml::image(app()->storage->bin($deal->image)->getFileUrl('original','http'), $deal->name, array('style'=>'width:400px'))
				: ''; ?>
		</div>
		<div style='margin-right: 410px;'>
			<div style='background: #E5ECF9; padding: 15px; text-align: center'>
				<p style='font-size: 18pt;'><?php echo m('payment')->format($deal->price); ?></p>
				<a href='<?php echo aUrl('/deal/view',array('url' => $deal->url),'http'); ?>' style='
					display: block;
					font-weight: bold;
					color: #000;
					text-align: center;
					border: 1px solid #666;
					padding: 5px;
					background: #f2f2f2;
				'><?php echo $this->t('See Today\'s Deal'); ?></a>
				<div style='padding-top: 10px;'>
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
			</div>
			<div style='padding-top:10px;'>
				<div style='padding: 10px; border: 1px solid #ccc'>
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
			?><div style='float: right; width: 200px; border: 2px solid #ccc; padding: 10px;'><h4><?php echo $this->t('More Great Deals'); ?></h4><?php
			foreach($side as $sdeal)
			{
				?><div style='border-bottom: 1px solid #ccc;'><?php
					echo CHtml::link($sdeal->name,aUrl('/deal/view',array('url' => $sdeal->url),'http'));
				?></div><?php
			}
			?></div><?php
		}
		?><div><?php echo $deal->description; ?></div>
	</div>
</div>