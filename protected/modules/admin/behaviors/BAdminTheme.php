<?php
class BAdminTheme extends UWorkletBehavior
{
	public function afterWorklet()
	{
		if(wm()->get('admin.helper')->layout())
			app()->theme = $this->getModule()->param('theme')?$this->getModule()->param('theme'):null;
	}
}