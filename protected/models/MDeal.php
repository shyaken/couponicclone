<?php
class MDeal extends UActiveRecord
{	
	public $currPrice;
	
	public static function module()
	{
		return 'deal';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{Deal}}';
	}
	
	public function relations()
	{
		return array(
			'company' => array(self::BELONGS_TO, 'MCompany', 'companyId'),
			'locs' => array(self::HAS_MANY, 'MDealLocation', 'dealId', 'together' => true),
			'redeemLocs' => array(self::HAS_MANY, 'MDealRedeemLocation', 'dealId'),
			'imageBin' => array(self::BELONGS_TO, 'MStorageBin', 'image'),
			'stats' => array(self::HAS_ONE, 'MDealStats', 'id'),
			'cache' => array(self::HAS_MANY, 'MDealCache', 'dealId'),
			'reviews' => array(self::HAS_MANY, 'MDealReview', 'dealId'),
			'coupons' => array(self::HAS_MANY, 'MDealCoupon', 'dealId'),
			'media' => array(self::HAS_MANY, 'MDealMedia', 'dealId', 'order' => '`order` ASC'),
			'i18n' => array(self::HAS_MANY, 'MI18N', 'relatedId', 'on' => "model='Deal'"),
			'categories' => array(self::MANY_MANY, 'MDealCategory', '{{DealCategoryAssoc}}(dealId,categoryId)', 'together' => true),
			'campaign' => array(self::HAS_ONE, 'MDealSubscriptionCampaign', 'id'),
			'list' => array(self::HAS_ONE, 'MSubscriptionList', 'relatedId', 'on' => 'list.type=1'),
			'prices' => array(self::HAS_MANY, 'MDealPrice', 'dealId'),
			'mainPrice' => array(self::HAS_ONE, 'MDealPrice', 'dealId', 'on' => 'mainPrice.main=1'),
		);
	}
	
	public function cacheValue($name)
	{
		foreach($this->cache as $c)
			if($c->name == $name)
				return $c->value;
	}
	
	public function rules()
	{
		return array(
			array('companyId','safe'),
			array('id, url, companyId, location, timeZone, name, value, price, start, end, expire, purchaseMin, purchaseMax, limitPerUser, finePrint, highlights, image, keywords, description, active, status', 'safe', 'on'=>'search'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'companyId' => $this->t('Company'),
			'company.name' => $this->t('Company'),
			't.name' => $this->t('Name'),
		);
	}
	
	public function isExpired()
	{
		return $this->expire && $this->expire < time();
	}
	
	public function getName()
	{
		return $this->priceOption?$this->priceOption->name:null;
	}
	
	public function getValue()
	{
		return $this->priceOption?$this->priceOption->value:null;
	}
	
	public function getPrice()
	{
		return $this->priceOption?$this->priceOption->price:null;
	}
	
	public function getDealPrice()
	{
		return $this->priceOption?$this->priceOption->dealPrice:null;
	}
	
	public function getDiscount()
	{
		return $this->priceOption?$this->priceOption->discount:null;
	}
	
	public function getPriceOption()
	{
		static $opts = array();
		if($this->currPrice)
		{
			if(!isset($opts[$this->currPrice]))
				$opts[$this->currPrice] = MDealPrice::model()->findByPk($this->currPrice);
			return $opts[$this->currPrice];
		}
		else
			return $this->mainPrice;
	}
	
	public function getFinePrint()
	{
		return $this->translate('finePrint');
	}
	
	public function getHighlights()
	{
		return $this->translate('highlights');
	}
	
	public function getDescription()
	{
		return $this->translate('description');
	}
	
	public function getMetaKeywords()
	{
		return $this->translate('metaKeywords');
	}
	
	public function getMetaDescription()
	{
		return $this->translate('metaDescription');
	}
}