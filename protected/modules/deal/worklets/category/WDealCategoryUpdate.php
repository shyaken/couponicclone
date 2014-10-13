<?php
class WDealCategoryUpdate extends UFormWorklet {
	
    public $modelClassName = 'MDealCategoryForm';
    public $primaryKey='id';
    public $space = 'inside';
    
    public function title()
	{
		return $this->isNewRecord
			? $this->t('Add Category')
			: $this->t('Update Category');
	}
    
    public function accessRules() {
        return array(
            array('allow', 'roles' => array('administrator')),
            array('deny', 'users' => array('*')),
        );
    }
    
    public function properties() {
        return array(
            'elements' => array(
               'name' => array(
					'type' => 'UI18NField', 'attributes' => array(
						'type' => 'text',
						'languages' => wm()->get('base.language')->languages(),
					), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				),
                'url' => array('type' => 'text', 'hint' => aUrl('/c') . '/', 'layout' => "{label}\n<span class='hintInlineBefore'>{hint}\n{input}</span>"),
                'enabled' => array('type'=>'checkbox'),
            ),
            'buttons' => array(
                'submit' => array('type' => 'submit',
                        'label' => $this->isNewRecord?$this->t('Add'):$this->t('Save'),
                ),
                'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
            ),
            'model' => $this->model
        );
    }
    
    public function ajaxSuccess() {
        $listWorklet = wm()->get('deal.category.list');
        $message = $this->isNewRecord ? $this->t('Deal category has been successfully added.') : $this->t('Deal category has been successfully updated.');
        $json = array(
            'info' => array(
                'replace' => $message,
                'fade' => 'target',
                'focus' => true,
            ),
            'content' => array(
                'append' => CHtml::script('$.fn.yiiGridView.update("' . $listWorklet->getDOMId() . '-grid")')
            ),
        );
        if ($this->isNewRecord)
            $json['load'] = url('/deal/category/update', array('ajax' => 1, 'id' => $this->model->id));

        wm()->get('base.init')->addToJson($json);
    }
    
    public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
	
	public function afterSave()
	{	
		wm()->get('base.helper')->translations('DealCategory',$this->model,'name');
	}
}