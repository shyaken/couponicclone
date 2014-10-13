<?php
class WDealAdminUpdate extends UFormWorklet
{
	public $modelClassName = 'MDealNameForm';
	
	public function title()
	{
		return $this->t('Create New Deal');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeAccess()
	{
		if(isset($_GET['id']))
		{
			if(!$this->company() || !wm()->get('deal.edit.helper')->authorize($_GET['id']))
			{
				$this->accessDenied(null, $this->t('You can edit only deals that have "draft" status.'));
				return false;
			}
		}
		else
		{
			if(!app()->user->checkAccess('citymanager') && (!$this->company()
				|| !app()->user->checkAccess('company.edit',$this->company())))
			{
				$this->accessDenied();
				return false;
			}
		}
	}
	
	public function taskCompany()
	{
		static $company;
		if(!isset($company))
			$company = isset($_GET['id'])
				? MDeal::model()->findByPk($_GET['id'])->company
				: MCompany::model()->find('userId=?',array(app()->user->id));
		return $company;
	}
	
	public function beforeBuild()
	{
		if(isset($_GET['id']))
		{
			app()->request->redirect(url('/deal/edit/general',array('id' => $_GET['id'])));
			return $this->show = false;
		}
	}
	
	public function afterModel()
	{
		if(app()->user->checkAccess('citymanager'))
			$this->model->setScenario('admin');
		else
			$this->model->companyId = $this->company()->id;
	}
	
	public function properties()
	{
		if($this->isNewRecord)
		{
			$this->model->priority = 1;
			$this->model->active = 0;
			$this->model->status = 1;
			$this->model->timeZone = app()->param('timeZone');
			$this->model->useCredits = 1;
		}
		
		$c = new CDbCriteria;
		if(!app()->user->checkAccess('administrator') && app()->user->checkAccess('citymanager'))
			$c = wm()->get('agent.citymanager.helper')->applyCriteria($c, 'company');
		
		
		return array(
			'elements' => array(
				'companyId' => array('type' => 'dropdownlist',
					'items' => CHtml::listData(wm()->get('company.helper')->list($c),'id','name')),
				'name' => array(
					'type' => 'UI18NField', 'attributes' => array(
						'type' => 'text',
						'languages' => wm()->get('base.language')->languages(),
					), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Create'))
			),
			'model' => $this->model
		);
	}
	
	public function afterConfig()
	{
		if(!app()->user->checkAccess('citymanager'))
			unset($this->properties['companyId']);
	}
	
	public function beforeSave()
	{
		$this->model->url = $this->genUrl();
	}
	
	public function afterSave()
	{
		// saving initial price option
		$m = new MDealPriceForm;
		$m->dealId = $this->model->id;
		$m->main = 1;
		$m->save(false);
		
		$m->name = $this->model->name;
		
		// saving name translations
		wm()->get('base.helper')->translations('DealPrice',$m,'name');
		wm()->get('deal.edit.helper')->dealName($this->model->id, $this->model->name);
		
		// adding default location and redeem location - taking them from company location
		$company = MCompany::model()->findByPk($this->model->companyId);
		if(wm()->get('location.helper')->validLocation($company->location))
		{
			$dl = new MDealLocation;
			$dl->dealId = $this->model->id;
			$dl->location = $company->location;
			$dl->save();
		}
		$drl = new MDealRedeemLocation;
		$drl->dealId = $this->model->id;
		$drl->location = $company->location;
		$drl->zipCode = $company->zipCode;
		$drl->address = $company->address;
		$drl->save();
		
		// redirect to general edit form
		$this->successUrl = url('/deal/edit/general',array('id' => $this->model->id));
		if($this->isNewRecord && !app()->user->checkAccess('administrator') && app()->user->checkAccess('citymanager'))
			wm()->get('agent.citymanager.helper')->grantAccess($this->model->id, 'deal');
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->title;
		return $r;
	}
	
	public function taskRenderOutput()
	{
		if(app()->user->checkAccess('citymanager') && MCompany::model()->count() < 1)
			$this->render('error',array(
				'error' => $this->t('You need to create at least one company to be able to create deals.')));
		else
			return parent::taskRenderOutput();
	}
	
	public function taskGenUrl()
	{
		$url = 'deal-';
		$i = 0;
		$exists = true;
		while($exists)
		{
			$i++;
			$exists = MDeal::model()->exists('url=?', array($url.$i));
		}
		return $url.$i;
	}
}