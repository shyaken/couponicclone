<div id="<?php echo $id; ?>" class="grid-view">
<table class="items">
<thead>
<tr>
	<th><?php echo $this->t('Description'); ?></th>
	<th><?php echo $this->t('Quantity'); ?></th>
	<th><?php echo $this->t('Price'); ?></th>
	<th><?php echo $this->t('Total'); ?></th>
</tr>
</thead>
<tbody>
<tr class="odd">
	<td><?php echo $deal->name; ?></td>
	<td>
		<?php 
			echo CHtml::textField('quantity', $this->model->quantity, array('id'=>'quantityField'));
			echo CHtml::button($this->t('Update'), array('id'=>'quantityFieldUpdate'));
		?>
	</td>
	<td><?php echo m('payment')->format($deal->price); ?></td>
	<td><?php echo m('payment')->param('cSymbol').'<span id="total">'
		. ($this->model->quantity * $deal->price).'</span>'; ?></td>
</tr>
</tbody>
</table>
</div>