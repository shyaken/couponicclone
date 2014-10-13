<?php

class WCompanyAdminUpdate extends UFormWorklet {

	public $modelClassName = 'MCompanyForm';
	public $primaryKey = 'id';

	public function title() {
		return $this->isNewRecord ? $this->t('Add New Company') : $this->t('Edit Company Info');
	}

	public function accessRules() {
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users' => array('*'))
		);
	}

	public function beforeAccess() {
		if (isset($_GET['id']) && !app()->user->checkAccess('administrator') && app()->user->checkAccess('citymanager')) {
			$locations = array($this->model()->location);
			if (!wm()->get('agent.citymanager.helper')->checkAccess($this->model()->id, $locations, 'company')) {
				$this->accessDenied();
				return false;
			}
		}
	}

	public function properties() {
		$pwdBlockId = 'pwdB_' . CHtml::$count++;

		return array(
			'elements' => array(
				'account' => array('type' => 'UForm', 'elements' => array(
						'<h4>' . $this->t('Account Info') . '</h4>',
						'email' => array('type' => 'text'),
						'password' => array('type' => 'UJsButton', 'attributes' => array(
								'label' => $this->t('Change'),
								'callback' => '$("#' . $pwdBlockId . '").toggle();'
						)),
						'<div id="' . $pwdBlockId . '" style="display:none">',
						'newPassword' => array('type' => 'password'),
						'passwordRepeat' => array('type' => 'password'),
						'</div>',
						'timeZone' => array('type' => 'dropdownlist',
							'items' => include(app()->basePath . DS . 'data' . DS . 'timezones.php')),
					), 'model' => $this->accountModel()),
				'<h4>' . $this->t('Access Level') . '</h4>',
				'role' => array('type' => 'radiolist', 'label' => $this->t('User Role'),
					'items' => array(
						'company' => $this->t('Viewer: user can only view deals and their stats'),
						'company.editor' => $this->t('Editor: user can view and create/edit deals'),
					), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"
				),
				'<h4>' . $this->t('Payment Info') . '</h4>',
				'payment' => array('type' => 'text', 'hint' => $this->t('Paypal account address')),
				'<h4>' . $this->t('Company Info') . '</h4>',
				'name' => array('type' => 'text'),
				'website' => array('type' => 'text'),
				'zipCode' => array('type' => 'text', 'class' => 'short'),
				'address' => array('type' => 'text'),
				'phone' => array('type' => 'text'),
				'dummy1' => '<h4>' . $this->t('Commission') . '</h4>',
				'dummy2' => '<p>' . $this->t('You can set your special commission for the company. It will override system default setting.') . '</p>',
				'commission' => array('type' => 'text', 'hint' => '%',
					'label' => $this->t('Commission'),
					'class' => 'short', 'layout' => "{label}\n<span class='hintInlineAfter'>{input}\n{hint}</span>"),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord ? $this->t('Add') : $this->t('Save'))
			),
			'model' => $this->model
		);
	}

	public function accountModel() {
		static $m;
		if (!isset($m)) {
			Yii::import('application.modules.user.models.MUserAccountForm');
			$m = $this->isNewRecord ? new MCompanyUserForm : MCompanyUserForm::model()->findByPk($this->model->userId);
			if ($this->isNewRecord)
				$m->timeZone = app()->param('timeZone');
		}
		return $m;
	}

	public function taskModel() {
		if (!$this->model && !app()->user->checkAccess('citymanager')) {
			$this->model = MCompanyForm::model()->find('userId=?', array(app()->user->id));
			if ($this->model instanceOf CActiveRecord)
				$this->isNewRecord = $this->model->isNewRecord;
		}
		return parent::taskModel();
	}

	public function afterModel() {
		if (app()->user->checkAccess('citymanager'))
			$this->model->scenario = 'admin';
	}

	public function afterConfig() {
		if (!app()->user->checkAccess('citymanager')) {
			unset($this->properties['elements']['account']);
			unset($this->properties['elements']['role']);
			unset($this->properties['elements']['dummy1']);
			unset($this->properties['elements']['dummy2']);
			unset($this->properties['elements']['commission']);
			unset($this->properties['elements'][0]);
		}
		if ($this->isNewRecord)
			$this->accountModel()->role = 'company';
		$this->model->role = $this->accountModel()->role;
	}

	public function afterCreateForm() {
		if (app()->user->checkAccess('citymanager') && isset($this->properties['elements']['account']))
			$this->form['account']->model->firstName = $this->form->model->name;
		
		if($this->form['account']->model->email && !MUser::model()->exists('email=?', array($this->form['account']->model->email)))
			$this->form['account']->model->scenario = 'newUser';
		
	}

	public function beforeSave() {
		$userModel = $this->form['account']->model;
		if ($userModel->isNewRecord) {
			$userOld = MUser::model()->find('email=?', array($userModel->email));
			if ($userOld)
				$this->form['account']->model = $userOld;
		}
	}

	public function afterSave() {
		if (app()->user->checkAccess('citymanager')) {
			$userModel = $this->form['account']->model;
			$userModel->role = $this->model->role;
			$userModel->save();

			$this->form->model->userId = $userModel->id;
			$this->form->model->save();

			if ($this->isNewRecord && !app()->user->checkAccess('administrator'))
				wm()->get('agent.citymanager.helper')->grantAccess($this->model->id, 'company');
		}
	}

	public function beforeBuild() {
		$b = $this->attachBehavior('location.form', 'location.form');
		$b->ignoreFixed = true;
		if (!app()->request->isAjaxRequest && app()->user->checkAccess('citymanager'))
			wm()->add('company.admin.menu');
	}

	public function taskBreadCrumbs() {
		$bC = array();
		if (app()->user->checkAccess('citymanager'))
			$bC[$this->t('Companies')] = url('/company/admin/list');
		else
			$bC[$this->t('Company Admin')] = url('/company/admin');
		$bC[] = $this->title;
		return $bC;
	}

	public function ajaxSuccess() {
		$message = $this->isNewRecord ? $this->t('Company has been successfully added.') : $this->t('Company info has been successfully updated.');
		wm()->get('base.init')->addToJson(array('info' => array(
				'replace' => $message,
				'fade' => 'target',
				'focus' => true
				)));
		if ($this->isNewRecord)
			wm()->get('base.init')->addToJson(array('redirect' => url('/company/admin/update', array('id' => $this->model->id))));
	}

}