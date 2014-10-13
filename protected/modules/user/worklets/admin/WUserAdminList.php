<?php
class WUserAdminList extends UListWorklet
{
	public $modelClassName = 'MUserListModel';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Manage Users');
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Name'),'name' => 'lastName',
				'value' => '$data->name'),
			array('header' => $this->t('Email'),'name' => 'email'),
			array('header' => $this->t('IP'),'name' => 'ip',
				'value' => 'wm()->get("user.admin.list")->ips($data->ip,true)',
				'type' => 'raw'),
			array('header' => $this->t('Role'),'name' => 'role',
				'value' => 'wm()->get("user.admin.helper")->getRole($data->role)',
				'filter' => wm()->get('user.admin.helper')->roles()),
		);
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Users') => url('/user/admin/list')
		);
	}
	
	public function beforeBuild()
	{
		wm()->add('user.admin.menu');
	}
	
	public function ips($data,$firstOnly=false)
	{
		$data = preg_replace('/[,]{2,}/',',',$data);
		$ar = array_unique(explode(",",trim($data,',')));
		if(count($ar))
		{
			if($firstOnly)
			{
				$ret = $ar[0];
				if(count($ar)>1)
					$ret.= ' <a href="#" title="'.implode(", ", $ar).'">...</a>';
				return $ret;
			}
			else
				return implode("\n", $ar);
		}
	}
}