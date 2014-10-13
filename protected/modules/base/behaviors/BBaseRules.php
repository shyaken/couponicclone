<?php
class BBaseRules extends UWorkletBehavior
{
	public function afterUrlRules($rules)
	{
		return CMap::mergeArray($rules, array(
			'base/rss/<_a>/<_b>' => 'base/rss/location/<_a>:<_b>',
			'base/rss/<_a>' => 'base/rss/location/<_a>',
		));
	}
}