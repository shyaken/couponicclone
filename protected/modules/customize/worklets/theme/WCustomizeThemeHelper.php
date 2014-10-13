<?php
class WCustomizeThemeHelper extends USystemWorklet
{
	public function title()
	{
		return $this->t('Themes');
	}
	
	public function description()
	{
		return $this->t('Here you can manage your themes and customize colors.');
	}
	
	public function taskList()
	{
		$themes = array();
		$folders = app()->file->set(Yii::getPathOfAlias('webroot.themes'))->contents;
		foreach($folders as $f)
		{
			$id = basename($f);
			if(($obj = app()->themeManager->getTheme($id))!==null)
			{
				$themes[] = array(
					'id' => $id,
					'name' => $obj->themeName
				);
			}
		}
		return $themes;
	}
	
	public function taskCss($name,$theme=null)
	{
		$theme = $theme?app()->themeManager->getTheme($theme):app()->theme;
		if(file_exists(app()->theme->getViewPath(true).DS.'_config'.DS.$name.'.css')
			&& ($scheme = MThemeColorScheme::model()->find('themeId=? AND current=?', array($theme->name,1)))!==null)
				return cs()->registerCssFile($this->renderCss($name, $scheme));
	}
	
	public function taskRenderCss($name, $scheme)
	{
		$target = app()->runtimePath.DS.'theme'.DS.$scheme->themeId.DS.$name.'.css';
		$theme = app()->themeManager->getTheme($scheme->themeId);
		
		if(!file_exists($target))
		{		
			$this->createDir($theme);
			$colors = unserialize($scheme->value);
			$config = $this->colors($theme);
			
			$data = array();
			foreach($colors as $k=>$v)
			{
				$key = '{'.$k.'}';
				$data[$key] = $v;
				$files = $this->colorizeFileRequired($k,$config);
				if($files)
					foreach($files as $f => $o)
					{
						if(is_numeric($f))						
							$this->colorizeFile($o,$theme,$v);
						else
							$this->colorizeFile($f,$theme,$v,$o);
					}
			}
	
			$output = strtr($this->cssTemplate($theme, $name), $data);
			file_put_contents($target, $output);
			
			$this->dropAssets($scheme->themeId);
		}
		
		$path = asma()->publish(app()->runtimePath.DS.'theme'.DS.$scheme->themeId);
		return $path.'/'.$name.'.css';
	}
	
	public function taskCssTemplate($theme, $name)
	{
		return file_get_contents($theme->getViewPath(true).DS.'_config'.DS.$name.'.css');
	}
	
	public function taskCreateDir($theme)
	{
		$folder = app()->runtimePath.DS.'theme';
		if(!file_exists($folder))
			mkdir($folder, 0755);
		if(!file_exists($folder.DS.$theme->name))
			mkdir($folder.DS.$theme->name, 0755);
	}
	
	public function taskDropCache($themeId)
	{
		$path = app()->runtimePath.DS.'theme'.DS.$themeId;
		if(file_exists($path))
		{
			$this->dropAssets($themeId);
			app()->file->set($path)->purge();
		}
	}
	
	public function taskDropAssets($themeId)
	{
		$path = app()->runtimePath.DS.'theme'.DS.$themeId;
		if(file_exists($path))
		{
			$assets = asma()->getPublishedPath($path);
			if(file_exists($assets))
				app()->file->set($assets)->delete(true);
		}
	}
	
	public function taskColorizeFileRequired($id,$config)
	{
		foreach($config as $group)
			if(isset($group['items'][$id]) && isset($group['items'][$id]['files']))
				return $group['items'][$id]['files'];
		return false;
	}
	
	public function taskColorizeFile($file, $theme, $color, $overlay=false)
	{
		$source = $this->filePath($file,$theme);
		$overlay = $overlay ? $this->filePath($overlay,$theme) : false;
		
		Yii::import('uniprogy.extensions.image.Image');
		$image = new Image($source);
		$image->colorize(str_replace('#','',$color), $overlay);
		$image->save(app()->runtimePath.DS.'theme'.DS.$theme->name.DS.basename($source));
	}
	
	public function taskFilePath($alias,$theme)
	{
		$alias = str_replace('THEME',$theme->name,$alias);
		$ext = substr($alias,strrpos($alias,'.'));
		$alias = substr($alias,0,strrpos($alias,'.'));
		return Yii::getPathOfAlias($alias).$ext;
	}
	
	public function taskColors($theme)
	{
		static $colors = array();
		if(!isset($colors[$theme->name]))
			$colors[$theme->name] = require($theme->getViewPath(true).DS.'_config'.DS.'colors.php');
		return $colors[$theme->name];
	}
	
	public function taskColor($id, $theme=null)
	{
		static $scheme;
		$theme = $theme?app()->themeManager->getTheme($theme):app()->theme;
		
		if(!isset($scheme))
			$scheme = MThemeColorScheme::model()->find('themeId=? AND current=?', array($theme->name,1));

		if($scheme!==null && ($color = $scheme->color($id))!==null)
			return $color;
		
		$colors = $this->colors($theme);
		foreach($colors as $group)
			if(isset($group['items'][$id]))
				return $group['items'][$id]['default'];
		return null;
	}
}