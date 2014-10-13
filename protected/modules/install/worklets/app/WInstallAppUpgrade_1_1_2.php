<?php
class WInstallAppUpgrade_1_1_2 extends UInstallWorklet
{
	public $fromVersion = '1.1.1';
	public $toVersion = '1.1.2';
	
	public function getModule()
	{
		return app();
	}
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
		    'mailPriority' => '3',
		    'mailCharSet' => 'utf-8',
		    'mailEncoding' => '8bit',
		    'mailMailer' => 'mail',
		    'mailSendmail' => '/usr/sbin/sendmail',
		    'mailHost' => 'localhost',
		    'mailPort' => '25',
		    'mailSMTPSecure' => '',
		    'mailSMTPAuth' => '0',
		    'mailUsername' => '',
		    'mailPassword' => '',
		    'mailTimeout' => '10',
		));
	}
}