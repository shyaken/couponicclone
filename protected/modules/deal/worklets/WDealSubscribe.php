<?php
class WDealSubscribe extends UFormWorklet
{
	public $modelClassName = 'MDealSubscribeForm';
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function properties()
	{
		$location = wm()->get('deal.helper')->location();
		if($location)
			$this->model->location = $location;
		return array(
			'action' => array('/deal/subscribe'),
			'elements' => array(
				'location' => array('type' => 'dropdownlist', 'label' => $this->t('City'),
					'items' => wm()->get('location.helper')->locationsAsList(),
					'required' => true),
				'category' => array(
                    'type' => 'checkboxlist',
                    'items' => CHtml::listData(wm()->get('deal.category.helper')->categories(), 'id', 'name'),
                    'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
                    'hint' => $this->t('Do not check any to subscribe to all categories'),
					'label' => $this->t('Category'),
                ),
				'email' => array('type'=>'text', 'label'=>$this->t('Your email address')),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Subscribe'))
			),
			'model' => $this->model
		);
	}
	
	public function afterConfig()
	{
		if($this->param('categories') > 0)
			unset($this->properties['elements']['location']);
		elseif($this->param('categories') < 0)
			unset($this->properties['elements']['category']);
	}
	
	public function taskRenderOutput()
	{
		$cacheKey = $this->getCacheKeyPrefix() . 'render';
		if($this->beginCache($cacheKey, array('duration'=>param('maxCacheDuration'))))
		{
			$this->render('application.modules.base.views.worklets.hideLink',array('name'=>$this->getDOMId()));
			parent::taskRenderOutput();
			$this->endCache();
		}
	}
	
	public function taskSave()
	{
		$lists = array();
		if($this->param('categories') <= 0)
		{
			if(!$this->model->location)
				return $this->model->addError('email', $this->t('Please select city.'));
			$lists[] = array('type' => 0, 'relatedId' => $this->model->location);
		}
		if($this->param('categories') >= 0)
		{
			if(is_array($this->model->category) && count($this->model->category))
				foreach($this->model->category as $cat)
					$lists[] = array('type' => 2, 'relatedId' => $cat);
			else
				$lists[] = array('type' => 2, 'relatedId' => 0);
		}
		
		foreach($lists as $list)
			if($list != '')
				wm()->get('subscription.helper')->addEmailToList($this->model->email,$list);
		wm()->get('base.helper')->saveToCookie('subscribed','1');
	}
	
	public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array(
			'info' => $this->t('You have been successfully subscribed! Thank you!'),
			'content' => '<!-- # -->',
		));
	}
}