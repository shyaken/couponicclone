<h3><?php echo $this->t('Installation Complete'); ?></h3>
<p><?php echo $this->t('Application has been successfully installed!'); ?></p>
<p><?php echo $this->t('It is recommended to rename /protected/modules/install/controllers/DefaultController.php file to something else to prevent unauthorized attempts to install/upgrade this application.'); ?></p>
<p><?php echo CHtml::link('Click here to open Installer Home',url('/install/home')); ?></p>
<?php
$reports = wm()->get('install.helper')->getReports();
foreach($reports as $r)
{
	?><div class='box'><?php echo $r; ?></div><?php
}