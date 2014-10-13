<?php
class BLocationBackground extends UWorkletBehavior
{
	public function beforeRenderPage()
	{
		if(wm()->get('base.init')->renderType == 'normal'
			&& !cs()->isCssRegistered('custom.background')
			&& !wm()->get('admin.helper')->layout())
		{
			$location = wm()->get('deal.helper')->location();
			$preset = MLocationPreset::model()->find('location=?', array($location));
			if($preset && $preset->background)
				cs()->registerCss('custom.background','body {background: url('.app()->storage->bin($preset->background)->getFileUrl('original').') no-repeat top left fixed;}');
		}
	}
}