<?php
class WUserProfileUpdate extends UFormWorklet
{
	public $modelClassName = 'MUserProfileSetting';
	public $primaryKey='id';
	public $space = 'inside';
	
	public function title()
	{
		return $this->isNewRecord
			? $this->t('Add New Profile Field')
			: $this->t('Edit Profile Field');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
    {
		if($this->isNewRecord)
		{
			$this->model->type = 'text';
			$this->model->rules = '0';
		}
		
        return array(
            'elements' => array(
				'label' => array('type' => 'text', 'label' => $this->t('Label')),
                'type' => array('type' => 'dropdownlist',
					'label' => $this->t('Type'),
					'items' => wm()->get('user.profile.helper')->types(),
				),
				'<div class="itemList">',
					'items' => array('type' => 'textarea', 'hint' => $this->t('One option per line.'),
						'label' => $this->t('Options')),
				'</div>',
				'rules' => array('type' => 'radiolist', 'label' => $this->t('Required'),
					'items' => array(
						'0' => $this->t('No'),
						'1' => $this->t('Yes'),
					),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				),               
            ),
            'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
                'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
            ),
            'model' => $this->model
        );
    }
	
	public function taskRenderOutput()
	{
		parent::taskRenderOutput();
		$att = 'type';
		$name = CHtml::resolveName($this->model,$att);
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().' select[name=\''.$name.'\']").change(function(){
			if($(this).val() == "dropdownlist" || $(this).val() == "radiolist")
				$(".itemList").show();
			else
				$(".itemList").hide();
		});jQuery("#'.$this->getDOMId().' select[name=\''.$name.'\']").change();');
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('user.profile.list');
		$message = $this->isNewRecord
			? $this->t('New field has been successfully added.')
			: $this->t('Field has been successfully updated.');
		$json = array(
			'info' => array(
				'replace' => $message,
				'fade' => 'target',
				'focus' => true,
			),
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$listWorklet->getDOMId(). '-grid")')
			),
		);
		if($this->isNewRecord)
			$json['load'] = url('/user/profile/update', array('ajax'=>1,'id' => $this->model->id));
			
		wm()->get('base.init')->addToJson($json);
	}
}