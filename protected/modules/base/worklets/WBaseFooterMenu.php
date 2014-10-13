<?php
class WBaseFooterMenu extends UMenuWorklet
{
	public $space = 'inside';
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function properties()
	{
		return array(
			'items'=>array(
				array('label'=>$this->t('Home'), 'url'=>aUrl('/')),
				array('label'=>$this->t('About Us'), 'url'=>url('base/page', array('view' => 'about-us'))),
				array('label'=>$this->t('Contact Us'), 'url'=>url('base/contact')),
				array('label'=>$this->t('Privacy Policy'), 'url'=>url('base/page', array('view' => 'privacy'))),
				array('label'=>$this->t('Terms of Use'), 'url'=>url('base/page', array('view' => 'terms'))),
				array('label'=>$this->t('Switch to Mobile Version'),
					'url'=>url('base/setting', array(
						'name' => 'ignoreMobile',
						'value' => '0',
						'next' => '1',
					)),
					'visible' => wm()->get('base.helper')->isMobile(true)),
				
			),
			'htmlOptions'=>array(
				'class' => 'horizontal clearfix'
			)
		);
	}
}