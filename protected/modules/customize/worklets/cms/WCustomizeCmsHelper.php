<?php
class WCustomizeCmsHelper extends USystemWorklet
{
	public function title()
	{
		return $this->t('CMS');
	}
	
	public function description()
	{
		return $this->t('Here you can create and add HTML blocks or even whole static pages to your site.');
	}
	
	public function taskSaveContent($id,$content){
		
		$file = fopen($this->dir().DS.$id.'.php', "w");
		fwrite($file, $content);
		fclose($file);
	}
	
	public function taskReadContent($id){
		$file = $this->dir().DS.$id.'.php';
		if(is_file($file))
			require $file;
	}
	
	public function taskDir(){
		$dir = app()->basePath.DS.'runtime'.DS.'cms';
		if(!is_dir($dir))
			mkdir($dir, 0777); 
		return $dir;
	}
}