<?php
class WAdminHome extends UWidgetWorklet
{
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Admin Home');
	}
	
	public function taskRenderOutput()
	{
		$info[] = array(
			$this->t('Application Name') => app()->getTitle(),
			$this->t('Application Version') => app()->getVersion(),
			$this->t('Yii Framework Version') => Yii::getVersion(),	
			$this->t('UniProgy Framework Version') => Yii::getUniprogyVersion(),
		);
		
		$gmtNow = UTimestamp::getNow();
		
		$criteria = new CDbCriteria;
		$criteria->condition = 't.status=1 AND t.active=1
			AND (stats.bought IS NULL OR t.purchaseMax IS NULL
				OR t.purchaseMax=0 OR stats.bought < t.purchaseMax)
			AND t.start <= :time AND t.end > :time';
		$criteria->params = array(':time' => $gmtNow);
		
		$info[] = array(
			$this->t('Total Registered Users') => MUser::model()->count(),
			$this->t('Unverified Users') => MUser::model()->count('role=?',array('unverified')),
			$this->t('Unapproved Users') => MUser::model()->count('role=?',array('unapproved')),
			$this->t('Banned Users') => MUser::model()->count('role=?',array('banned')),
			$this->t('Current Active Deals') => MDeal::model()->with('stats')->count($criteria),
		);
		
		$info[] = array(
			$this->t('Admin Console') => CHtml::link(aUrl('/admin'),aUrl('/admin')),
			$this->t('City Manager Control Panel') => CHtml::link(aUrl('/agent/citymanager'),aUrl('/agent/citymanager')),
		);
		
		$this->render('home',array('info'=>$info));
	}
}