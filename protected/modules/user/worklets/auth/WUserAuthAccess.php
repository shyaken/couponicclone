<?php
Yii::import('application.modules.base.worklets.auth.WBaseAuthAccess');
class WUserAuthAccess extends WBaseAuthAccess
{	
	public function taskGranted()
	{
		$banned = explode(";", $this->param('bannedIPs'));
		$ip = app()->request->getUserHostAddress();
		foreach($banned as $rule)
		{
			if($rule===$ip || (($pos=strpos($rule,'*'))!==false && !strncmp($ip,$rule,$pos)))
				//return true;
				return false;
		}
		return true;
	}
}