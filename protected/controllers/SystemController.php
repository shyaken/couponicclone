<?php
class SystemController extends UController
{	
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction'
			)
		);
	}
	
	/**
	 * Special "error" action - it is being used to display all application errors.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	// changing controller layout to "system"
	    	$this->layout = 'system';
	    	// echo'ing message if this is an ajax requets
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	// rendering error page otherwise
	    	else
	    	{
	    		wm()->get('base.init')->reset()->renderPage(
	    			$this->renderPartial('error', $error, true)
	    		);
	    	}
	    }
	}
}