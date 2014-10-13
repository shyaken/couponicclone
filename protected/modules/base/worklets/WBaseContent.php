<?php
class WBaseContent extends UWidgetWorklet
{
	public $content;
	
	public function taskRenderOutput()
	{
		echo $content;
	}
}