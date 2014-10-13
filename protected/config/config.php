<?php
return CMap::mergeArray(
	require(dirname(__FILE__) . DS . 'public' . DS . 'instance.php'),
	CMap::mergeArray(
		require(dirname(__FILE__) . DS . 'public' . DS . 'modules.php'),
		require(dirname(__FILE__) . DS . 'main.php')
	)
);