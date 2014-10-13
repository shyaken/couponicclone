<?php
class WCompanyHelper extends USystemWorklet
{
	/**
	 * @return array list of all companies
	 */
	public function taskList($criteria=null)
	{
		$criteria = $criteria ? $criteria : new CDbCriteria;
		return MCompany::model()->findAll($criteria);
	}
}