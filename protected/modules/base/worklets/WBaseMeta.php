<?php
class WBaseMeta extends UMetaWorklet
{
	public function metaData()
	{
		$md = array(
			'index' => array(
				'title' => ''
			),
		);
		if(app()->controller->action->id == 'page' && isset($_GET['view']))
		{
			switch($_GET['view'])
			{
				case 'how-it-works':
					$md['page']['title'] = $this->t('How {name} Works',array('{name}'=>app()->name));
					break;
			}
		}
		return $md;
	}
}
