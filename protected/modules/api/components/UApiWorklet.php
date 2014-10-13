<?php
class UApiWorklet extends UWidgetWorklet
{
	public $layout = false;
	public $data = array();
	public $status = 'true';
	public $_errorMessage = '';
    public $_errorCodes = array("" => 0,
                                "Authentication required."=>1,
                                "Authentication required"=>1,
                                "Coupon can not be found."=>31,
                                "No active deal." => 32,
                                "Email or password is incorrect."=>3,
                                "Error getting list of languages."=>4,                                   
                                "Access denied." => 5,
                                "Missing required parameter." => 10,
                                "Missing required parameter 'email'." => 11, 
                                "Missing required parameter 'password'." => 12,
                                "Missing required parameter 'code'." => 13,
                                "Missing required parameter 'id'." => 14,
                                "Missing required parameter 'location'." => 15,
                                "Missing required parameter 'language'." => 16,
                                "Invalid value." => 20,
                                "Invalid code value." => 21,
                                "Invalid id value." => 22,
                                   );
	
	public function taskConfig()
	{
		wm()->get('base.init')->renderType = 'ajax-no-scripts';
		parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{
		Header('Content-Type:text/xml; charset=UTF-8');
		$this->render('view',array('data'=>$this->data
                                   , 'status' => $this->status
                                   , 'errorCode' => $this->GetErrorCode()
                                   , 'errorMessage' => $this->errorMessage));
	}
    
    public function GetErrorCode(){
        if(isset( $this->_errorCodes[$this->errorMessage]))
            return $this->_errorCodes[$this->errorMessage];
        return 100;
    }
    
	public function setErrorMessage($value){
		$this->status = 'false';
		$this->_errorMessage = $value;
		$this->renderOutput();
		die;
	}
	
	public function getErrorMessage(){
		return $this->_errorMessage;
	}
	
	public function getRequiredParam($name){
		$parm = app()->request->getParam($name,null);
		if (is_null($parm))
			$this->errorMessage = $this->t('Missing required parameter {name}.', array(
				'{name}' => $name
			));
		return $parm;
		
	}	
	
}