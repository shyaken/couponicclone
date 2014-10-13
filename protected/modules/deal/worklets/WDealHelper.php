<?php

class WDealHelper extends USystemWorklet {

	private static $_models = array();
	private $_location;

	/**
	 * @return integer current user location ID
	 */
	public function taskLocation() {
		if (!isset($this->_location)) {
			$location = null;
			// if location has been submitted
			if (isset($_GET['location'])) {
				$location = wm()->get('location.helper')->urlToLocation($_GET['location']);
				// and saving into the cookie
				if ($location)
					wm()->get('base.helper')->saveToCookie('location', $location);
			}

			// if location has not been found trying to extract it from the cookie
			if (!$location)
				$location = wm()->get('base.helper')->getFromCookie('location');

			// if location has not been found or it is not valid
			// setting user location to the default one
			if (!$location || !wm()->get('location.helper')->validLocation($location)) {
				$location = wm()->get('location.helper')->defaultLocation();
				wm()->get('base.helper')->saveToCookie('location', $location);
			}
			$this->_location = $location;
		}
		return $this->_location;
	}

	/**
	 * Location setter.
	 * @param integer location id
	 */
	public function taskSetLocation($location) {
		$this->_location = $location;
		wm()->get('base.helper')->saveToCookie('location', $location);
	}

	/**
	 * Deal model factory.
	 * @param integer deal ID
	 * @return MDeal model instance
	 */
	public function taskDeal($id) {
		if (!isset(self::$_models[$id]))
			self::$_models[$id] = MDeal::model()->findByPk($id);
		return self::$_models[$id];
	}

	/**
	 * @param MDeal deal model
	 * @param integer user ID
	 * @return array list of user coupons for a certain deal
	 */
	public function taskUserCoupons($deal, $id=null) {
		$id = $id ? $id : app()->user->id;
		return MDealCoupon::model()->count('userId=? AND dealId=?', array(
			$id, $deal->id
		));
	}

	/**
	 * New order ping.
	 * @param MDeal deal model
	 * @param MPaymentOrderItem order item model
	 */
	public function taskNewOrder($deal, $item) {
		// if current deal status is "1" (not yet tipped, but running)
		// and current order makes the "bought" number climb over "purchaseMin"
		// saving current time and number of "bought" as tipped time and tipped amount
		if ($deal->status == 1 && $deal->stats && $deal->stats->bought >= $deal->purchaseMin)
			if ($deal->stats->bought - $item->quantity < $deal->purchaseMin) {
				$this->dealCache($deal->id, 'tippedTime', time());
				$this->dealCache($deal->id, 'tippedAmount', $deal->stats->bought);
			}
	}

	/**
	 * Verifies whether the deal is tipped at the moment.
	 * @param MDeal deal model
	 */
	public function taskVerifyTipped($deal) {
		// check current deal status and the difference between "bought" and "purchaseMin"
		if ($deal->status == 1 && $deal->stats && $deal->stats->bought >= $deal->purchaseMin) {
			// updating cache info
			if (!$deal->cacheValue('tippedAmount')
					|| $deal->stats->bought <= $deal->cacheValue('tippedAmount')) {
				$this->dealCache($deal->id, 'tippedTime', time());
				$this->dealCache($deal->id, 'tippedAmount', $deal->stats->bought);
			}
		}
		// the deal is no longer tipped - remove cached info
		else {
			MDealCache::model()->deleteAll('name=? AND dealId=?', array(
				'tippedTime', $deal->id
			));
			MDealCache::model()->deleteAll('name=? AND dealId=?', array(
				'tippedAmount', $deal->id
			));
		}
	}

	/**
	 * Saves data into special deals cache.
	 * @param integer deal ID
	 * @param string item name
	 * @param string item value
	 * @param boolean whether to overwrite existing data with the same name
	 */
	public function taskDealCache($id, $name, $value, $overwrite=true) {
		$m = MDealCache::model()->findByAttributes(array('dealId' => $id, 'name' => $name));
		if (!$m)
			$m = new MDealCache;
		elseif (!$overwrite)
			return;

		$m->dealId = $id;
		$m->name = $name;
		$m->value = $value;
		$m->save();
	}

	/**
	 * @param integer deal ID
	 * @return MDealStats model instance
	 */
	public function taskDealStats($id) {
		$stats = MDealStats::model()->findByPk($id);
		if (!$stats) {
			$stats = new MDealStats;
			$stats->id = $id;
		}
		return $stats;
	}

	/**
	 * @return array list of available deal statuses
	 */
	public function taskStatusList() {
		return array(
			'draft' => $this->t('Drafts'),
			'awaiting' => $this->t('Awaiting Approval'),
			'active_' => $this->t('All Active'),
			'active' => $this->t('Running'),
			'tipped' => $this->t('Tipped'),
			'closed' => $this->t('Closed'),
			'paid' => $this->t('Paid'),
			'cancelled' => $this->t('Cancelled'),
		);
	}

	/**
	 * @param MDeal deal model
	 * @return string current deal status plus "Unknown" if status is not found
	 */
	public function taskStatus($model) {
		$list = $this->statusList();
		$status = $this->dealStatus($model);
		return isset($list[$status]) ? $list[$status] : $this->t('Unknown');
	}

	/**
	 * @param MDeal deal model
	 * @return string current deal status
	 */
	public function taskDealStatus($model, $applyAdjust=false) {
		if ($model->active == 0)
			return 'draft';
		elseif ($model->active == 2)
			return 'awaiting';

		$bought = $model->stats ? $model->stats->bought : 0;
		if ($applyAdjust)
			$bought+= $model->statsAdjust;

		switch ($model->status) {
			case '1':
				if (($model->purchaseMax && $bought >= $model->purchaseMax)
						|| $model->end <= time())
					return 'closed';
				elseif ($bought >= $model->purchaseMin)
					return 'tipped';
				else
					return 'active';
				break;
			case '2':
				return 'cancelled';
				break;
			case '3':
				return 'paid';
				break;
		}
	}

	/**
	 * @param MDeal deal model
	 * @return boolean whether this deal is current according to it's start and end dates
	 */
	public function taskTodays($model) {
		$now = time();
		return $model->start <= $now && $model->end >= $now;
	}

	/**
	 * @param MDeal deal model
	 * @return boolean whether this deal is available according to it's start date and status
	 */
	public function taskAvailable($model) {
		return $model->start < time()
		&& in_array($this->dealStatus($model), array('tipped', 'active'));
	}

	public function taskEmailCampaign($deal, $side) {
		// we need to create an email campaign for this deal
		$campaign = MDealSubscriptionCampaign::model()->findByPk($deal->id);
		if (!$campaign) {
			$campaign = new MDealSubscriptionCampaign;
			$campaign->id = $deal->id;
		}

		$m = app()->mailer;

		// temporarily switching theme to the currently selected
		$cfg = require(Yii::getPathOfAlias('application.config.public.modules') . '.php');
		$oldTheme = app()->theme;
		app()->theme = isset($cfg['theme']) ? $cfg['theme'] : $oldTheme;

		$m->prepare('dummy@email.com', 'dealEmail', array(
			'deal' => $deal,
			'side' => $side,
			'renderLayout' => false,
		));

		// switching theme to the old one
		app()->theme = $oldTheme;

		// getting lists array
		$w = wm()->get('subscription.helper');
		$lists = array();
		if ($this->param('categories') >= 0) {
			foreach ($deal->categories as $category)
				$lists[] = $w->getList(2, $category->id, true)->id;
		}
		if ($this->param('categories') <= 0) {
			foreach ($deal->locs as $l)
				if ($l->location == 0)
					$lists[] = $w->getOverallList()->id;
				else
					$lists[] = $w->getList(0, $l->location, true)->id;
		}

		$data = array(
			'subject' => $m->Subject,
			'plainBody' => param('htmlEmails') ? $m->AltBody : $m->Body,
			'htmlBody' => param('htmlEmails') ? $m->Body : null,
			'lists' => $lists,
			'schedule' => $deal->start,
		);
		if ($campaign->campaignId)
			$data['id'] = $campaign->campaignId;
		$campaign->campaignId = wm()->get('subscription.helper')->addCampaign($data);
		$campaign->save();

		$m->reset();
	}

	public function taskBypassSubscription() {
		return array(
			'deal/subscription',
			'base/page',
			'user/signup',
			'user/login',
			'user/resend',
			'user/restore',
			'user/reset',
			'user/verify',
			'admin/index',
			'admin/cron',
			'install/index',
			'api/*'
		);
	}

	public function taskSaveLocation($data, $id) {
		$session = $this->session;
		$session['loc.' . $id] = $data;
		$this->session = $session;
	}

	public function taskCurrentLocation($id) {
		$session = $this->session;

		if (isset($session['loc.' . $id]))
			$model = MDealRedeemLocation::model()->findByPk($session['loc.' . $id]);
		else {
			$price = MDealPrice::model()->findBypk($id);
			$model = $price ? MDealRedeemLocation::model()->find('dealId=?', array($price->dealId)) : null;
			$session['loc.'.$id] = $model->id;
			$this->session = $session;
		}

		if (!$model)
			return;

		return $this->t('Redeem Location: {loc} [change]', array(
			'{loc}' => wm()->get('location.helper')->locationAsText($model->loc, $model->address, $model->zipCode, ' ')
		));
	}

	public function taskShareLink($params=array()) {
		return aUrl('/deal/view', $params);
	}

}