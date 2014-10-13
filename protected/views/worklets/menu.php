<?php
if($this->dropdown)
	app()->controller->widget('uniprogy.extensions.cdropdownmenu.CDropDownMenu',$this->properties);
else
	app()->controller->widget('UMenu',$this->properties);