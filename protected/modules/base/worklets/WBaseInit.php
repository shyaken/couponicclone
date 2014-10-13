<?php
class WBaseInit extends USystemWorklet
{
	public $show = false;
	public $requireSecure = false;
	private $_json = array();
	private $_renderType = 'normal';
	private $_states;
	
	/**
	 * @return array list of default JavaScript files that need to be attached to every page
	 */
	public function js()
	{
		if(wm()->get('base.helper')->isMobile())
		{
			cs()->scriptMap['jquery.js'] = asma()->publish(Yii::getPathOfAlias('uniprogy.framework.js.jquery').'-1.4.2.min.js');
			cs()->scriptMap['jquery.min.js'] = asma()->publish(Yii::getPathOfAlias('uniprogy.framework.js.jquery').'-1.4.2.min.js');
			return array(
				'jquery.scrollTo-min.js',
				'jquery.uniprogy.js',
				'jqtouch' => array(
					'jqtouch.js',
					'jqtouch.css',
				),
			);
		}
		
		return array(
			'jquery.scrollTo-min.js',
			'jquery.uniprogy.js',
			'jquery.uniprogy.binds.js',
		);
	}
	
	public function taskBuild()
	{
		$this->config();
		$this->worklet();
		$this->renderPage();
	}
	
	/**
	 * Initialization: verify current language, set render type to "ajax"
	 * if this is an ajax request. Register scripts otherwise.
	 */
	public function taskConfig()
	{
		$this->language();
		if(app()->request->isAjaxRequest)
			$this->setRenderType('ajax');
		else
			$this->registerScripts();
			
		if(wm()->get('base.helper')->isMobile() && app()->theme)
			app()->theme->customPath = 'mobile';
	}
	
	/**
	 * Sets the current application language by checking the associated cookie value.
	 */
	public function taskLanguage()
	{
		$languages = wm()->get('base.language')->languages();
		$language = wm()->get('base.helper')->getFromCookie('language');
		if(!$language && !app()->user->isGuest && m('user') && app()->user->model()->language)
			$language = app()->user->model()->language;
		
		if($language && is_array($languages) && array_key_exists($language, $languages))
			app()->language = $language;
	}
	
	/**
	 * Loads the worklet which is associated with the current route.
	 * If no such worklet can be found it generates an error.
	 */
	public function taskWorklet()
	{
		$route = app()->getController()->getRouteEased();
		$worklet = wm()->get(str_replace("/", ".", $route));
		if($worklet)
			wm()->addCurrent($worklet);
		else
			app()->controller->missingAction(app()->controller->action->id);
	}
	
	/**
	 * Renders the page after all other procedures are complete.
	 * @param string optional content to render
	 */
	public function taskRenderPage($content=null)
	{
		// if this page requires secure connection (and it's not)
		// we need to redirect (and otherwise)
		$redirect = null;
		if($this->requireSecure && !app()->request->isSecureConnection)
			$redirect = str_replace('http','https',app()->request->getHostInfo())
				. app()->request->url;
		elseif(!$this->requireSecure && app()->request->isSecureConnection
			&& !app()->request->isAjaxRequest)
			$redirect = str_replace('https','http',app()->request->getHostInfo())
				. app()->request->url;
		
		if($redirect)
		{
			if(app()->user->hasFlash('info'))
			{
				$info = app()->user->getFlash('info');
				app()->user->setFlash('info', $info);
			}
			
			app()->request->redirect($redirect);
			app()->end();
		}
		
		// setting all default headers
		foreach($this->headers() as $h)
			header($h);
			
		// render the page differently depending on the current render type
		switch($this->getRenderType())
		{
			// normal and mobile page render will first prepare meta data
			// then it will record all clips (worklets)
			// finally it will render using renderText
			case 'normal':
				$this->metaData();
				$this->recordClips();
				app()->controller->renderText($content);
				break;
			// ajax page render first records clips (worklets)
			// then it renders "ajax" layout with an empty content
			case 'ajax':
				$this->recordClips();
				app()->controller->layout = 'ajax';
				app()->controller->renderText('');
				break;
			// same as "ajax" but without scripts
			case 'ajax-no-scripts':
				$this->recordClips();
				cs()->reset();
				app()->controller->layout = 'ajax';
				app()->controller->renderText('');
				break;
			// json render will simply render the "json" layout file
			case 'json':
				app()->controller->renderFile(
					app()->controller->getLayoutFile('json')
				);
				break;
		}
	}
	
	/**
	 * @return array list of HTTP headers
	 */
	public function taskHeaders()
	{
		return array('Content-type: text/html; charset=utf-8');
	}
	
	/**
	 * Builds page meta data
	 */
	public function taskMetaData()
	{
		// first we need to grab current worklet's meta data
		$metaData = array();
		if($w = wm()->getCurrentWorklet())
			$metaData = $w->meta();
		
		// add meta data from special module-level meta data worklet
		$metaData = app()->controller->module
			&& ($w = wm()->get(UFactory::getModuleAlias(app()->controller->module).'.meta'))!==false
				? CMap::mergeArray($w->get(),$metaData)
				: CMap::mergeArray(UMetaWorklet::defaultMetaData(),$metaData);
		
		// set page title
		$title = null;
		// if current page title property starts with '*' it means we should
		// simply remove the '*' and use it without doing ANY changes
		if(strpos(app()->controller->pageTitle, '*')===0)
			$metaData['title'] = substr(app()->controller->pageTitle, 1);
		// otherwise extra title from meta data
		else{
			if(empty($metaData['title']))
				$metaData['title'] = array(app()->name);
			else
				$metaData['title'] = array($metaData['title'],' - ',app()->name);
				
			if(app()->params['poweredBy'])
			{
				$metaData['title'][] = ' - ';
				$metaData['title'][] = strip_tags(app()->params['poweredBy']);
			}
			
			$metaData['title'] = app()->locale->textFormatter->format($metaData['title']);
		}
			
		app()->controller->pageTitle = $metaData['title'];
		
		// add keywords and description meta tags
		cs()->registerMetaTag($metaData['keywords'], 'keywords');
		cs()->registerMetaTag($metaData['description'], 'description');
	}
	
	/**
	 * Registers preset JavaScript files.
	 */
	public function taskRegisterScripts()
	{
		$cs = app()->clientScript;
		$am = app()->getAssetManager();
		$cs->registerCoreScript('jquery');
		$files = array();
		foreach($this->js() as $pkg=>$file) {
			if(is_array($file))
			{
				$assets = $am->publish(UP_PATH.DS.'js'.DS.$pkg);
				foreach($file as $f)
					$files[] = $assets.'/'.$f;
			}
			else
				$files[] = $am->publish(UP_PATH.DS.'js'.DS.$file);
		}
		foreach($files as $f)
		{
			if(preg_match('/\.css$/',$f))
				$cs->registerCssFile($f);
			else
				$cs->registerScriptFile($f);
		}
	}
	
	/**
	 * Goes through all worklets that were added to the worklet manager during this session
	 * and renders them (the ones that are renderable). Output is stored to an appropriate clip - 
	 * depends on worklet space.
	 */
	public function taskRecordClips()
	{
		$pieces = array();
		foreach(wm()->worklets->toArray() as $id=>$worklet)
		{
			if(!$worklet->show)
				continue;

			if(app()->theme)
				$worklet = app()->theme->applyToWorklet($worklet);
			$space = $worklet->space;			
			
			if(!isset($pieces[$space]))
				$pieces[$space] = new CList;
			
			$pieces[$space]->add($id);			
		}
				
		foreach($pieces as $space => $ids)
		{
			$orderer = new UWorkletOrderer($ids);
			$ids = $orderer->order();
			
			app()->controller->beginClip($space);
			foreach($ids as $id)
				wm()->worklets->itemAt($id)->run();
			app()->controller->endClip();
		}
	}
	
	/**
	 * Clears all clips and worklet manager.
	 */
	public function taskReset()
	{
		wm()->clear();
		app()->controller->getClips()->clear();
		return $this;
	}
	
	/**
	 * @return array list of special URL replacement rules
	 */
	public function taskUrlRules()
	{
		return array('page/<view>' => 'base/page');
	}
	
	/**
	 * @param string route
	 * @param mixed currently found owner (can be a module)
	 * @return UController controller instance
	 */
	public function taskCreateController($route,$owner=null)
	{
		return null;
	}
	
	/**
	 * Clears JSON stack.
	 */
	public function clearJson()
	{
		$this->_json = array();
	}
	
	/**
	 * Adds data into the JSON stack.
	 * @param array data to add
	 */
	public function addToJson($data)
	{
		$this->_json = CMap::mergeArray($this->_json, $data);
		$this->setRenderType('json');
	}
	
	/**
	 * @return array JSON stack
	 */
	public function getJson()
	{
		return $this->_json;
	}
	
	/**
	 * Render Type setter.
	 * @param string render type
	 */
	public function setRenderType($value)
	{
		$this->_renderType = $value;
	}
	
	/**
	 * @return string render type
	 */
	public function getRenderType()
	{
		return $this->_renderType;
	}
	
	/**
	 * @return CAttributeCollection states collection
	 */
	public function getStates()
	{
		if(!isset($this->_states))
			$this->_states = new CAttributeCollection;
		return $this->_states;
	}
	
	/**
	 * States setter.
	 * @param string item key
	 * @param string item value
	 */
	public function setState($key,$value)
	{
		$states = $this->getStates();
		$states->add($key,$value);		
	}
}