<?php
class WSubscriptionInstallUpgrade_1_4_0 extends UInstallWorklet
{
	public $fromVersion = '1.3.4';
	public $toVersion = '1.4.0';
	
	public function taskSuccess()
	{
		parent::taskSuccess();
		$models = MSubscriptionListEmail::model()->findAll();
		foreach($models as $m)
		{
			$exists = MSubscriptionEmail::model()->exists('email=?', array($m->emailBackup));
			if(!$exists)
			{
				$n = new MSubscriptionEmail;
				$n->email = $m->emailBackup;
				$n->hash = $m->hash;
				$n->save();
				MSubscriptionListEmail::model()->updateAll(array('emailId' => $n->id), 'emailBackup=?', array(
					$m->emailBackup
				));
			}
		}
	}
	
}