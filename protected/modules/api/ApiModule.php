<?php
class ApiModule extends UWebModule
{
	public function preinit()
	{
		if(!YII_DEBUG)
		{
			Yii::addCustomClasses(array(
				'UApiWorklet' => $this->basePath.'/components/UApiWorklet.php',
			));
		}
		return parent::preinit();
	}
    
    public function getTitle()
    {
        return 'API';
    }
}
