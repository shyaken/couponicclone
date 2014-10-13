<?php
class WDealBarcode extends UWidgetWorklet
{
	public $barcode;
	
	public function taskConfig()
	{
		wm()->get('base.init')->setRenderType('ajax-no-scripts');
		$this->barcode = isset($_GET['barcode'])?$_GET['barcode']:null;
		if(!$this->barcode)
			return $this->show = false;
	}
	
	public function taskRenderOutput()
	{
		require($this->module->basePath.'/extensions/phpqrcode/qrlib.php');
		QRcode::png($this->barcode, false, 'L', 7);
		app()->end();
	}
}