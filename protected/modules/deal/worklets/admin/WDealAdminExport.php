<?php
class WDealAdminExport extends UWidgetWorklet
{	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskConfig()
	{
		$list = wm()->get('deal.admin.coupon');
		$_GET[$list->modelClassName] = $_POST[$list->modelClassName];
		$list->init();
		$dataProvider = $list->dataProvider();
		$dataProvider->pagination = false;
		
		$cr = $dataProvider->getCriteria();
		$sqlCommand = 'SELECT user.email,user.firstName,user.lastName,deal.expire,t.* FROM {{DealCoupon}} t
			LEFT JOIN {{Deal}} deal ON t.dealId = deal.id
			LEFT JOIN {{User}} user ON t.userId = user.id WHERE '.$cr->condition;

		$dataReader=app()->db->createCommand($sqlCommand)->query($cr->params);
		
		$file = app()->basePath.DS.'runtime'.DS.'export-'.app()->user->id.'-'.time().'.csv';
		$handler = fopen($file,'w');
		fwrite($handler,$this->generateCSV($this->headers()));
		
		while(($r=$dataReader->read())!==false)
			fwrite($handler,$this->row($r));
			
		fclose($handler);
		$contents = file_get_contents($file);
		@unlink($file);
		
		$this->send($contents,$_POST['charset']);
	}
	
	public function taskHeaders()
	{
		return array(
			$this->t('ID'),
			$this->t('Order ID'),
			$this->t('Deal ID'),
			$this->t('Deal Name'),
			$this->t('Location'),
			$this->t('User'),
			$this->t('Status'),
		);
	}
	
	public function taskRow($row)
	{
		$result = array(
			"#".$row['dealId'].'-'.$row['orderId'].'-'.$row['hash'],
			$row['orderId'],
			$row['dealId'],
			$row['email']." [".txt()->format($row['firstName'],' ',$row['lastName'])."]",
			$row['status']==1
				? ($row['expire'] && $row['expire'] < time() ? $this->t('Expired') : $this->t('Available'))
				: $this->t('Used')
		);

		if($_POST['charset'] != 'utf-8')
		{
			$row[3] = iconv('utf-8', $_POST['charset'], $row[3]);
			$row[4] = iconv('utf-8', $_POST['charset'], $row[4]);
		}
		return $this->generateCSV($row);
	}
	
	public function taskGenerateCSV($row)
	{
		$csv = '';
		foreach($row as $item)
			$csv.= $this->escape($item).$this->param('delimiter');
		$csv = rtrim($csv,$this->param('delimiter'))."\r\n";
		return $csv;
	}
	
	public function taskEscape($value)
	{
		return '"'.str_replace('"','""',$value).'"';
	}
	
	public function taskSend($contents,$encoding='utf-8')
	{
		header('Cache-control: private');
		header('Pragma: private');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		
		header('Content-Encoding: '.$encoding);
		header('Content-type: text/csv; charset='.$encoding);
		header('Content-Disposition: attachment; filename=export.csv');
		if($encoding == 'utf-8')
			echo "\xEF\xBB\xBF"; // UTF-8 BOM		
		echo $contents;
		app()->end();
	}
}