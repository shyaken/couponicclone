<?php
class WSubscriptionAdminParams extends UParamsWorklet
{	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'emailsLimit' => array('type' => 'text',
					'label' => $this->t('Emails per run limit'), 'class' => 'short',
					'hint' => $this->t('Subscription module will be sending emails when the cron runs. Here you can set how much emails should it send per run. We do not recommend setting too high value because it may affect server performance.'))),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
}