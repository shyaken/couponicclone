<?php
class WAdminToolsMessage extends UFormWorklet
{	
	public $modelClassName = 'MAdminToolsMessageModel';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Language File Creator');
	}
	
	public function description()
	{
		return $this->t('This tool parses the whole script for words and phrases that need to be translated and returns a language package, which you can translate, upload to the server and get your site shown in another language.');
	}
	
	public function properties()
	{
		return array(
			'activeForm' => array(
				'class'=>'UActiveForm',
				'ajax'=>false
			),
			'description' => $this->description(),
			'elements' => array(
				'modules' => array('type' => 'checkboxlist', 'items' => $this->modules(),
					'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
				'themes' => array('type' => 'checkboxlist', 'items' => $this->themes(),
					'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
				'language' => array('type' => 'text', 'hint' => $this->t('Ex.: en_us')),
			),
			'model' => $this->model,
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Create Language File')),
			),
		);
	}
	
	public function taskSave()
	{
		$zipFiles = array();
		
		$config = require_once(app()->basePath.DS.'config'.DS.'messages.php');
		
		if($this->model->themes[0] == '*')
			unset($this->model->themes[0]);
		
		$appMessages = $this->getTranslatedMessages(app());
		$todo = array();
		foreach($this->model->modules as $mId)
		{
			// messages
			
			$module = $mId=='app'?app():UFactory::getModuleFromAlias($mId);
			$moduleMessages = $this->getTranslatedMessages($module);
			$key = $mId=='app' || app()->getIsAppModule($mId)
				? 'app' : $mId;
			if(!isset($todo[$key]))
				$todo[$key] = array(
					'translated' => $moduleMessages,
					'untranslated' => array(),
				);
			$todo[$key]['untranslated'] = array_merge($todo[$key]['untranslated'],
				$this->collectMessages($module,$config,$appMessages,$moduleMessages));
				
			// files that might need localized version
			
			$paths = array($module->viewPath=>$config);
			$paths = array_merge($paths,$this->getThemeViewPaths($module,$config));
			foreach($paths as $path=>$cfg)
			{
				$options = array();
				if(isset($cfg['fileTypes']))
					$options['fileTypes']=$cfg['fileTypes'];
				if(isset($cfg['exclude']))
					$options['exclude']=$cfg['exclude'];
				$files=CFileHelper::findFiles(realpath($path),$options);
				foreach($files as $file)
				{
					if($this->fileRequiresLocalizedVersion($file))
					{
						$existing = dirname($file).DS.$this->model->language.'/'.basename($file);
						
						$fileN = str_replace('\\','/',$file);
						$appBasePath = str_replace('\\','/',app()->basePath);
						$webrootPath = str_replace('\\','/',Yii::getPathOfAlias('webroot'));
						
						$target = str_replace($appBasePath,'protected',$fileN);						
						if($target === $file)
							$target = str_replace($webrootPath.'/','',$fileN);
						$target = str_replace('\\','/',$target);
						
						$pos = strrpos($target,'/');
						list($d,$f) = array(substr($target,0,$pos),substr($target,$pos+1));
						
						$target = $d.'/'.$this->model->language.'/'.$f;
						if(file_exists($existing))
							$zipFiles[$existing] = $target;
						else
							$zipFiles[$file] = $target;
					}
				}
			}
		}
		
		foreach($todo as $k=>$v)
		{
			$untranslated = array();
			$msgs = array_values(array_unique($v['untranslated']));
			foreach($msgs as $msg)
				$untranslated[$msg] = '';
			ksort($untranslated);
			$merged = array_merge($untranslated,$v['translated']);
			
			$sourceFile = app()->getRuntimePath().'/'.($k=='app'?'app':$k).'.uniprogy.php';
			$targetFile = 'protected' . ($k=='app'?'':'/modules/'.str_replace('.','/modules/',$k))
				. '/messages/' . $this->model->language . '/uniprogy.php';
			$array = str_replace("\r",'',var_export($merged,true));
			file_put_contents($sourceFile,"<?php\nreturn $array;\n");
			$zipFiles[$sourceFile] = $targetFile;
		}
		
		// create ZIP and send
		$zipFile = app()->getRuntimePath().DS.'language.'.$this->model->language.'.zip';
		if(file_exists($zipFile))
			@unlink($zipFile);
		$zip = Yii::createComponent(array('class'=>'uniprogy.extensions.zip.EZip'));
		$zip->makeZipFromFiles($zipFiles,$zipFile);
		
		app()->file->set($zipFile)->send();
		app()->end();
	}
	
	public function taskCollectMessages($module,$config,$appMessages,$moduleMessages)
	{
		$paths = array($module->basePath => $config);
		$paths = array_merge($paths,$this->getThemeViewPaths($module,$config));

		$messages = $this->getMessages($paths);
		$untranslated = array();
		foreach($messages as $m)
		{
			if(!isset($appMessages[$m]) && !isset($moduleMessages[$m]))
				$untranslated[] = $m;
		}
		
		return $untranslated;
	}
	
	public function getThemeViewPaths($module,$config)
	{
		$paths = array();
		$first=0;
		reset($this->model->themes);
		foreach($this->model->themes as $t)
		{
			if($module===app())
			{
				$path = Yii::getPathOfAlias('webroot.themes.'.$t.'.views');
				if(!$first)
				{
					$config['exclude'][] = '/worklets';
					foreach(app()->getModules() as $id=>$c)
						$config['exclude'][] = '/'.$id;
					$first = 1;
				}
				$paths[$path] = $config;
			}
			else
			{
				$p = Yii::getPathOfAlias('webroot.themes.'.$t.'.views.'.$module->getId());
				if(file_exists($p))
					$paths[$p] = $config;
				$p = Yii::getPathOfAlias('webroot.themes.'.$t.'.views.worklets.'.$module->getId());
				if(file_exists($p))
					$paths[$p] = $config;
			}
		}
		return $paths;
	}
	
	public function getTranslatedMessages($module)
	{
		$file = $module->basePath.DS.'messages'.DS.$this->model->language.DS.'uniprogy.php';
		return file_exists($file)?require($file):array();
	}
	
	public function taskGetMessages($paths)
	{
		$messages = array();
		foreach($paths as $path=>$config)
		{
			$options = array();
			if(isset($config['fileTypes']))
				$options['fileTypes']=$config['fileTypes'];
			if(isset($config['exclude']))
				$options['exclude']=$config['exclude'];
			$files=CFileHelper::findFiles(realpath($path),$options);
			foreach($files as $file)
				foreach($config['translators'] as $cfg)
					$messages=array_merge($messages,$this->extractMessages($file,$cfg));
		}
		return array_values(array_unique($messages));
	}
	
	public function taskExtractMessages($fileName,$cfg)
	{
		$subject=file_get_contents($fileName);
		$n=preg_match_all($cfg['regex'],$subject,$matches,PREG_SET_ORDER);
		$messages=array();
		for($i=0;$i<$n;++$i)
		{
			if(is_numeric($cfg['index']['category']))
			{
				$category = $matches[$i][$cfg['index']['category']];
				if(($pos=strpos($category,'.'))!==false)
					$category=substr($category,$pos+1,-1);
				else
					$category=substr($category,1,-1);
			}
			else
				$category = $cfg['index']['category'];

			if($category != 'uniprogy')
				continue;
					
			$message=$matches[$i][$cfg['index']['message']];
			$messages[]=eval("return $message;");  // use eval to eliminate quote escape
		}
		return $messages;
	}
	
	public function taskFileRequiresLocalizedVersion($fileName)
	{
		$dir = str_replace("\\","/",dirname($fileName));
		if(preg_match('/\/[a-z]{2}$/',$dir) || preg_match('/\/[a-z]{2}_[a-z]{2}$/',$dir))
			return false;
		$subject = file_get_contents($fileName);
		// strip php tags first
		$subject = preg_replace('#<\?(?:php)?(.*?)\?>#s','',$subject);
		// strip style tags
		$subject = preg_replace('#<(?:style)(.*?)>(.*?)<(?:/style)>#s','',$subject);
		// strips special html entities
		$subject = preg_replace('#&([^;]+);#s','',$subject);
		// strip all tags
		$subject = preg_replace('/[^A-Za-z]/','',strip_tags($subject));
		return $subject!='';
	}
	
	public function taskRenderOutput()
	{
		// modules checkbox
		$attr = 'modules';
		$opts = array();
		CHtml::resolveNameID($this->model,$attr,$opts);
		$id = '#'.$opts['id'].'_0';
		// themes checkbox
		$attr = 'themes';
		$opts = array();
		CHtml::resolveNameID($this->model,$attr,$opts);
		
		$id.= ', #'.$opts['id'].'_0';
		$script = 'jQuery("'.$id.'").change(function(){
			var c = $(this).is(":checked");
			$(this).closest(".row").find(":checkbox").attr({"checked": c});
		});';
		
		cs()->registerScript(__CLASS__,$script);
		
		parent::taskRenderOutput();
		$this->render('instruction');
	}
	
	public function taskBreadCrumbs()
	{
		return array(
			$this->t('Tools') => url('/admin/tools'),
			$this->title()
		);
	}
	
	public function taskModules($parent=null)
	{
		$modules = array();
		if(!$parent)
		{
			$parent = app();
			$modules['app'] = $this->t('Entire Application');
		}
		foreach($parent->getModules() as $id=>$c)
		{
			$m = $parent->getModule($id);
			if($m)
			{
				$key = UFactory::getModuleAlias($m);
				$modules[$key] = $m->getTitle();
				$modules = CMap::mergeArray($modules,$this->modules($m));
			}
		}
		return $modules;
	}
	
	public function taskThemes()
	{
		$themes = array('*' => $this->t('All Themes'));
		$dirs = app()->themeManager->getThemeNames();
		foreach($dirs as $dir)
		{
			$className = ucfirst($dir).'Theme';
			$file = app()->basePath.DS.'components'.DS.$className.'.php';
			if(file_exists($file))
				$themes[$dir] = call_user_func(array($className,'getThemeName'));
		}
		return $themes;
	}
}