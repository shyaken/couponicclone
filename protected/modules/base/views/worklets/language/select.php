<div class='langSelector'><?php
echo CHtml::link($this->t('Language').': '.$this->languages[app()->language],
	'#', array('name' => 'selector'));
?><div class='langList'><?php
foreach($this->languages as $code=>$name)
	echo CHtml::link($name,'#',array('name' => $code));
?></div></div>