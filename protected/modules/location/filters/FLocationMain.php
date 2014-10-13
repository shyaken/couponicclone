<?php
class FLocationMain extends UWorkletFilter
{
	public function filters()
	{
		return array(
			'base.init' => array('behaviors' => array('location.background')),
		);
	}
}