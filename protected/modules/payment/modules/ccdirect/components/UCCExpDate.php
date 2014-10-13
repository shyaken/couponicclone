<?php
class UCCExpDate extends CInputWidget
{
	public function run()
	{
		$value = $this->model->{$this->attribute};
		list($name,$id) = $this->resolveNameID();
		// month
		$month = isset($value['month']) ? $value['month'] : null;
		$data = locale()->getMonthNames('wide', true);
		echo CHtml::dropDownList($name.'[month]', $month, $data, array());
		//year
		$year = isset($value['year']) ? $value['year'] : null;
		$data = array();
		for($i=date('Y');$i<(date('Y')+6);$i++)
			$data[$i] = $i;
		echo CHtml::dropDownList($name.'[year]', $year, $data, array());
	}
}