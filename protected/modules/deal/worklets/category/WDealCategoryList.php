<?php
class WDealCategoryList extends UListWorklet {
    
    public $modelClassName = 'MDealCategory';
    
    public function title()
    {
            return $this->t('Deal Category');
    }
    
    public function accessRules()
    {
            return array(
                    array('allow', 'roles' => array('administrator')),
                    array('deny', 'users'=>array('*'))
            );
    }
    
    public function columns() 
    {
        return array(
            array('header'=>$this->t('Name'), 'name'=>'name', 'value' => '$data->name'),
            array('header'=>$this->t('Enabled'), 'name'=>'enabled', 'value'=> 'wm()->get("deal.category.list")->t($data->enabled?"Yes":"No")',
                'filter' => array('0' => 'No', '1' => 'Yes')),
            'buttons' => array(
				'class' => 'CButtonColumn',
				'buttons' => array(
                    'update' => array('click' => 'function(){
						$("#' . $this->getDOMId() . '").uWorklet().load({position:"appendReplace",url: $(this).attr("href")});
						return false;}'),
                ),
			)
        );
    }
    
    public function buttons() {
        $link = url('/deal/category/update');
		$id = $this->getDOMId().CHtml::ID_PREFIX.CHtml::$count++;
		cs()->registerScript($this->getId().$id,'jQuery("#' .$id. '").click(function(e){
		e.preventDefault();
		$.uniprogy.loadingButton("#'.$id.'",true);
		$("#' .$this->getDOMId(). '").uWorklet().load({
			url: "' .$link. '",
			position: "appendReplace", 
			success: function(){
				$.uniprogy.loadingButton("#'.$id.'",false);
			}
		});
		});');
		return array(CHtml::button($this->t('Create New Category'), array('id' => $id)));
    }
    
    public function taskBreadCrumbs()
    {
            $r = array();
            if(!app()->user->checkAccess('administrator'))
                    $r[$this->t('Company Admin')] = url('/company/admin');
            $r[$this->t('Deals')] = url('/deal/admin/list');
            $r[] = $this->t('Deal Categories');
            return $r;
    }
    
    public function beforeBuild() 
    {
        wm()->add('deal.admin.menu');
    }
}
