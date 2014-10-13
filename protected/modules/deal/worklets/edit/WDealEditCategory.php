<?php
class WDealEditCategory extends UFormWorklet
{
    public $modelClassName = 'UDummyModel';
        
    public function title()
	{
		return $this->t('{title}: Categories', array(
			'{title}' => $this->deal()->name
		));
	}
        
    public function taskDeal()
	{
		return MDeal::model()->findByPk($_GET['id']);
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
		if(isset($_GET['id']) && wm()->get('deal.edit.helper')->authorize($_GET['id']))
			return true;
		$this->accessDenied();
		return false;
	}
        
    public function properties()
    {
    	$cats = array();
    	foreach($this->deal()->categories as $m)
    		$cats[] = $m->id;
    		
    	$this->model->attribute = $cats;
    	
        return array(
            'elements' => array(
                'attribute' => array(
                    'type' => 'checkboxlist',
                    'items' => CHtml::listData(wm()->get('deal.category.helper')->categories(), 'id', 'name'),
                    'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
					'label' => $this->t('Categories'),
                ),
            ),
            'buttons' => array(
                'submit' => array('type' => 'submit',
                        'label' => $this->t('Save'))
            ),
            'model' => $this->model
        );
    }
    
    public function afterConfig()
    {
        wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
    }
    
    public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('administrator'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('Category');
		return $r;
	}
    
    public function taskSave()
    {
        MDealCategoryAssoc::model()->deleteAll('dealId='.$_GET['id']);
        if(!is_array($this->model->attribute))
        	return;
        foreach ($this->model->attribute as $attr) {
            $m = new MDealCategoryAssoc;
            $m->dealId = $_GET['id'];
            $m->categoryId = $attr;
            $m->save();
        }
    }
    
    public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array(
			'info' => array(
				'replace' => $this->t('Deal categories have been successfully updated.'),
				'fade' => 'target',
				'focus' => true,
			),
		));
	}
}