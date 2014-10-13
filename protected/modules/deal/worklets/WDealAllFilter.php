<?php
class WDealAllFilter extends UFormWorklet
{
	public $modelClassName = 'MDealAllFilterForm';
	
	public function properties() {
		if(!$this->model->location)
			$this->model->location = wm()->get('deal.helper')->location();
			
        return array(
        	'action' => url('/deal/allFilter'),
            'elements' => array(
                'location' => array('type' => 'dropdownlist', 'label' => $this->t('City'),
					'items' => wm()->get('location.helper')->locationsAsList()),
                'category' => array(
                    'type' => 'checkboxlist',
                    'items' => CHtml::listData(wm()->get('deal.category.helper')->categories(), 'id', 'name'),
                    'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
					'label' => $this->t('Category'),
                ),
            ),
            'buttons' => array(
                'submit' => array('type' => 'submit',
                        'label' => $this->t('Refine Search'))
            ),
            'model' => $this->model
        );
    }
	
	public function afterConfig()
	{
		if($this->param('categories') < 0)
			unset($this->properties['elements']['category']);
		elseif($this->param('categories') > 0)
			unset($this->properties['elements']['location']);
	}
    
    public function taskDescription()
    {
		$filter = '';
		if($this->param('categories') <= 0)
			$filter.= wm()->get('location.helper')->locationToData($this->model->location, true)->cityName;
    	if($this->param('categories') >= 0 && is_array($this->model->category) && count($this->model->category))
    	{
	    	$filter.= ' (';
	    	foreach($this->model->category as $id)
	    		$filter.= wm()->get('deal.category.helper')->category($id)->name.', ';
	    	$filter = rtrim($filter, ', ');
	    	$filter.= ')';
    	}
    	
    	return CHtml::tag('div', array('id' => 'filterInfo'), $this->t('Current Filter: {filter}', array(
    		'{filter}' => $filter
    	)).'<br />'.CHtml::link($this->t('Change'),'#',array('id' => 'changeFilterLink')));
    }
    
    public function beforeRenderOutput()
    {
    	echo $this->description();
    	cs()->registerScript(__CLASS__,'jQuery("#changeFilterLink").live("click",function(){
    		$("#'.$this->getDOMId().'").find("form").toggle();
    	});');
    }
    
    public function afterModel()
    {
    	$s = $this->session;
    	if(isset($s['settings']))
    	{
    		$settings = unserialize($s['settings']);
    		foreach($settings as $k=>$v)
    			$this->model->$k = $v;
    	}
    }
    
    public function taskSave()
    {
    	if(!$this->model->category)
    		$this->model->category = array();
    	$data = $this->model->attributes;
    	$s = $this->session;
    	$s['settings'] = serialize($data);
    	$this->session = $s;
    }
    
    public function ajaxSuccess()
    {
    	$w = wm()->get('deal.all');
    	wm()->get('base.init')->addToJson(array(
    		'content' => array('append' => CHtml::script('$("#'.$w->getDOMId().'").uWorklet().load({
				url: "'.url('/deal/all', array('type' => $w->typeSetting(), 'ajax' => 1)).'",
				position: "replace"
			});jQuery("#filterInfo").html("'.CJavaScript::quote($this->description()).'");'))
    	));
    }
}