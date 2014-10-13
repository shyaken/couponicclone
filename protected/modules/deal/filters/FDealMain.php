<?php
class FDealMain extends UWorkletFilter
{	
	public function filters()
	{
		return array(
			'base.index' => array('replace' => $this->homepage()),
			'base.init' => array('behaviors' => array('deal.controller')),
			'base.menu' => array('behaviors' => array('deal.menu')),
			'admin.menu' => array('behaviors' => array('deal.adminMenu')),
			'deal.admin.create' => array('replace' => array('deal.admin.update')),
			'payment.admin.list' => array('behaviors' => array('deal.paymentAdminList')),
			'user.helper' => array('behaviors' => array('deal.userSignup')),
			'user.admin.delete' => array('behaviors' => array('deal.userDelete')),
			'subscription.admin.removeNewsletter' => array('behaviors' => array('deal.removeNewsletter')),
			'base.footerMenu' => array('behaviors' => array('deal.footerMenu')),
			'payment.order' => array('behaviors' => array('deal.paymentOrder'))	
		);
	}
	
	public function homepage(){
		return array(m('deal')->params['homepage']);
    }
}