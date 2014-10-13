<?php
class WPaymentHelper extends USystemWorklet
{
	/**
	 * @param MUser user model
	 * @return integer current credit amount
	 */
	public function taskCredit($user=null)
	{
		$user=$user?$user:app()->user->model();
		return $user && $user->credit?$user->credit->amount:0;
	}
	
	/**
	 * Adds credit to the user account.
	 * @param integer amount to add
	 * @param MUser user model
	 */
	public function taskAddCredit($amount,$user=null,$message=null)
	{
		$user=$user?$user:app()->user->model();
		$credit = $user->credit;
		if(!$credit)
		{
			$credit = new MPaymentCredit;
			$credit->id = $user->id;
			$credit->amount = 0;
		}
		
		$credit->amount+= $amount;
		$credit->save();
		
		if($message)
		{
			$m = new MTransactionHistory;
			$m->userId = $user->id;
			$m->action = $amount >= 0 ? 'plus' : 'minus';
			$m->amount = $amount;
			$m->comment = $message;
			$m->date = time();
			$m->save();
		}
	}
}