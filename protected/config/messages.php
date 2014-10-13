<?php
return array(
	'translators' => array(
		array(
			'regex' => '/\bthis-\>t\s*\(\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*[,\)]/s',
			'index' => array('category' => 'uniprogy', 'message' => 1),
		),
		array(
			'regex' => '/\bYii::t\s*\(\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*,\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*[,\)]/s',
			'index' => array('category' => 1, 'message' => 2),
		),
	),
	'fileTypes' => array('php'),
	'exclude'=> array('.svn','/modules'),
);