<p><?php
	echo $this->t('Make sure directories and files listed below are given the right permissions').':';
?><table><?php
foreach($permissions as $item=>$p)
{
	?><tr><td><?php echo $item; ?></td><td><?php echo $p; ?></td></tr><?php
}
?></table>
</p>