<?php

class BCustomizeCmsBlock extends UWorkletBehavior {

	public function beforeRecordClips() {
		if(!wm()->currentWorklet 
				|| wm()->currentWorklet->id == 'deal.print' 
				|| wm()->currentWorklet->module->name == 'install')
			return;
			
		if (app()->request->isAjaxRequest || app()->controller->id == 'admin'
				|| (app()->controller->module && app()->controller->module->name == 'admin')
				|| wm()->get('base.init')->states['admin'])
			return;

		$c = new CDbCriteria;
		$c->condition = "(`show` IS NULL OR :route REGEXP `show`) AND (`hide` IS NULL OR :route NOT REGEXP `hide`)";
		$c->params = array(':route' => app()->controller->routeEased);

		$models = MCmsBlock::model()->findAll($c);

		if (!count($models))
			return;

		$w = $this->blankWorklet();
		foreach ($models as $m) {
			$params = array();
			parse_str($m->getParams, $params);
			$hide = false;
			foreach ($params as $key => $value)
				if (!isset($_GET[$key]) || $_GET[$key] != $value) {
					$hide = true;
					break;
				}
			if ($hide)
				continue;

			if (!app()->theme || app()->theme->resolveSpace($m->space)) {
				$clone = clone $w;
				$clone->model = $m;
				wm()->add($clone, $clone->id . '.' . $m->id);
			}
		}
	}

	public function blankWorklet() {
		return wm()->get('customize.cms.block');
	}

}