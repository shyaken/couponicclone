<?php
class WBaseLanguage extends UWidgetWorklet
{
	public $languages;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskLanguages()
	{
		return $this->param('languages');
	}
	
	public function taskConfig()
	{
		$this->languages = $this->languages();
		if(!is_array($this->languages) || count($this->languages)<=1)
			return $this->show = false;
	}
	
	public function taskRenderOutput()
	{
		$url = str_replace('LID', '',url('/base/setting', array('name' => 'language', 'value' => 'LID')));
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().' a:first").click(function(){
			$("#'.$this->getDOMId().' .langList").toggle();
			return false;
		});
		jQuery("#'.$this->getDOMId().' .langList a").click(function(){
			$.ajax({
				url: "'.$url.'" + $(this).attr("name"),
				method: "post",
				success: function() {
					location.reload();
				}
			});
			return false;
		});');
		$this->render('select');
	}
}