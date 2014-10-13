<?php $adminUrl = aUrl('/') .'/'. param('adminUrl'); ?>
<p><?php echo $this->t('{module} module has been successfully installed.', array('{module}' => ucfirst($this->module->name))); ?></p>
<p>
<?php echo $this->t('Admin Console: {link}', 
	array('{link}'=>CHtml::link($adminUrl,$adminUrl))); ?><br />
<?php echo $this->t('Admin Login: {login}', array('{login}'=>$email)); ?><br />
<?php echo $this->t('Admin Password: {password}', array('{password}'=>$password)); ?><br />
</p>
<p><strong><?php echo $this->t('SETUP YOUR CRON JOB'); ?></strong></p>
<p><?php echo $this->t('If your server allows you to setup cron jobs, please use details below.'); ?><br />
<?php echo $this->t('If not, you can run your crons manually from Admin Console -> Tools.'); ?></p>
<p>
	<?php echo $this->t('Cron command').':'; ?> wget --quiet --delete-after <?php echo aUrl('/'.app()->param('adminUrl').'/cron',array('s' => app()->param('cronSecret'))); ?><br />
	<?php echo $this->t('Time Period').':'; ?> */10 * * * *
</p>