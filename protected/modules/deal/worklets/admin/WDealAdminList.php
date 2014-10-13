<?php
class WDealAdminList extends UListWorklet
{
	public $modelClassName = 'MDealListModel';
	
	public function title()
	{
		return $this->t('Manage Deals');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company', 'citymanager')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskCompanyAccess()
	{
		static $a;
		if(!isset($a))
			$a = app()->user->checkAccess('company.edit',$this->company(),false);
		return $a;
	}
	
	public function taskCompany()
	{
		static $company;
		if(!isset($company))
			$company = MCompany::model()->find('userId=?',array(app()->user->id));
		return $company;
	}
	
	public function beforeConfig()
	{
		if(!app()->user->checkAccess('citymanager'))
			$_GET[$this->modelClassName]['companyId'] = $this->company()->id;
		if(!isset($_GET[$this->modelClassName]['status']))
			$_GET[$this->modelClassName]['status'] = 'active_';
	}
	
	public function columns()
	{
		$buttonsTemplate = '';
		if($this->companyAccess())
			$buttonsTemplate.= '{update} {delete}';
		
		$c = array(
			array('header' => $this->t('ID'), 'name' => 'id'),
			array('header' => $this->t('Name'), 'name' => 'nameSearch',
				'value' => '$data->name'),
			array('header' => $this->t('City'), 'name' => 'city',
				'value' => 'wm()->get("deal.admin.list")->city($data)',
				'type' => 'raw'),
			array(
				'name' => 'start',
				'header' => $this->t('Start'),
				'value' => '$data->start
					? app()->getDateFormatter()->formatDateTime(
					utime($data->start,false), "short", "short")
					: null',
			),
			array(
				'name' => 'end',
				'header' => $this->t('End'),
				'value' => '$data->end
					? app()->getDateFormatter()->formatDateTime(
					utime($data->end,false), "short", "short")
					: null',
			),
			array(
				'header' => $this->t('Discount'),
				'value' => '$data->discount."%"',
				'filter' => false,
			),
			array(
				'name' => 'views',
				'header' => $this->t('Views'),
				'value' => '($data->stats && $data->stats->views)?$data->stats->views:0',
				'filter' => false,
			),
			array(
				'name' => 'bought',
				'header' => $this->t('Bought'),
				'value' => '($data->stats && $data->stats->bought)?$data->stats->bought:0',
				'filter' => false,
			),
			array(
				'name' => 'status',
				'header' => $this->t('Status'),
				'value' => 'wm()->get("deal.helper")->status($data)',
				'filter' => wm()->get('deal.helper')->statusList(),
			),
		);
		
		if($buttonsTemplate)
		{
			$c['buttons'] = array(
				'class' => 'CButtonColumn',
				'template' => $buttonsTemplate
			);
		}
		else
			$this->addButtonColumn = false;
			
		if(app()->user->checkAccess('citymanager'))
		{
			array_unshift($c,array(
				'name' => 'companyId',
				'filter' =>  CHtml::listData(MCompany::model()->findAll(),'id','name'),
				'value' => '$data->company->name',
			));
		}
			
		return $c;
	}
	
	public function dataProvider()
	{
		$p = parent::dataProvider();
		if(!app()->user->checkAccess('administrator') && app()->user->checkAccess('citymanager'))
		{
			$c = $p->criteria;
			$c->with[] = 'locs';
			$c = wm()->get('agent.citymanager.helper')->applyCriteria($c, 'deal');
			$p->criteria = $c;			
		}
		return $p;
	}
	
	public function buttons()
	{
		if(!$this->companyAccess())
			return parent::buttons();
			
		$link = url('/deal/admin/update');
		$id = $this->getDOMId().CHtml::ID_PREFIX.CHtml::$count++;
		cs()->registerScript($this->getId().$id,'jQuery("#' .$id. '").click(function(e){
			e.preventDefault();
			window.location = "' .$link. '";
		});');
		return array(CHtml::button($this->t('Add New Deal'), array('id' => $id)));
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		return $r;
	}
	
	public function beforeBuild()
	{
		if(app()->user->checkAccess('administrator'))
		{
			wm()->add('deal.admin.order', null, array('position' => array('after' => $this->getId())));
			wm()->add('deal.admin.payment', null, array('position' => array('after' => $this->getId())));
			wm()->add('deal.admin.menu');
		}
		if(!$this->companyAccess())
		{
			$this->addButtonColumn = false;
			$this->addCheckBoxColumn = false;
			$this->addMassButton = false;
		}
		if(!app()->user->checkAccess('citymanager'))
			$this->addMassButton = false;
		
		if(app()->user->checkAccess('company') && (app()->user->checkAccess('administrator') || !app()->user->checkAccess('citymanager')))
			wm()->add('deal.admin.coupon', null, array('position' => array('after' => $this->getId())));
	}
	
	public function taskCity($data)
	{
		$ret = '';
		foreach($data->locs as $l)
		{
			if($l->location == 0)
				return $this->t('All Locations');
			else
				$ret.= wm()->get('location.helper')->locationAsText($l->loc).'<br />';
		}
		return $ret;
	}
	
	public function afterConfig()
	{
		$this->options = array(
			'rowCssClassExpression' => '$this->rowCssClass[$row%count($this->rowCssClass)]
				. (!app()->user->checkAccess("citymanager") && $data->active!=0
					? " noEdit" : "")',
		);
	}
}