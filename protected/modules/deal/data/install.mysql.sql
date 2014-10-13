DROP TABLE IF EXISTS `{{Deal}}`;
CREATE TABLE `{{Deal}}` (
	`id`				bigint unsigned not null auto_increment,
	`url`				varchar(250),
	`companyId`			bigint unsigned,
	`location`			bigint unsigned,
	`address`			text,
	`timeZone`			double,
	`start`				bigint unsigned,
	`end`				bigint unsigned,
	`redeemStart`		bigint unsigned,
	`expire`			bigint unsigned,
	`purchaseMin`		int unsigned,
	`purchaseMax`		int unsigned,
	`limitPerUser`		int unsigned,
	`image`				bigint unsigned,
	`background`		bigint unsigned,
	/* 0 - draft, 1 - active, 2 - awaiting approval */
	`active`			tinyint unsigned,
	/* 1 - active, 2 - cancelled, 3 - paid */
	`status`			tinyint unsigned,
	`priority`			tinyint unsigned,
	`commission`		double unsigned,
	`paymentOptions`	varchar(250),
	`useCredits`		tinyint unsigned,
	`requireRedeemLoc`	tinyint unsigned,
	`statsAdjust`		int unsigned,

	primary key(`id`),
	index(`companyId`),
	index(`location`),
	unique(`url`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealLocation}}`;
CREATE TABLE `{{DealLocation}}` (
	`id`				bigint unsigned not null auto_increment,
	`dealId`			bigint unsigned,
	`location`			bigint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealRedeemLocation}}`;
CREATE TABLE `{{DealRedeemLocation}}` (
	`id`				bigint unsigned not null auto_increment,
	`dealId`			bigint unsigned,
	`location`			bigint unsigned,
	`address`			text,
	`zipCode`			varchar(100),
	`lon`				double,
	`lat`				double,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealReview}}`;
CREATE TABLE `{{DealReview}}` (
	`id`				bigint unsigned not null auto_increment,
	`dealId`			bigint unsigned,
	`name`				varchar(250),
	`website`			varchar(250),
	`review`			text,
	primary key(`id`),
	index(`dealId`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealStats}}`;
CREATE TABLE `{{DealStats}}` (
	`id`				bigint unsigned,
	`views`				bigint unsigned,
	`bought`			bigint unsigned,
	
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealCache}}`;
CREATE TABLE `{{DealCache}}` (
	`id`				bigint unsigned not null auto_increment,
	`dealId`			bigint unsigned,
	`name`				varchar(250),
	`value`				double unsigned,
	
	primary key(`id`),
	unique(`dealId`,`name`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealCoupon}}`;
CREATE TABLE `{{DealCoupon}}` (
	`id`				bigint unsigned not null auto_increment,
	`orderId`			bigint unsigned,
	`dealId`			bigint unsigned,
	`priceId`			bigint unsigned,
	`userId`			bigint unsigned,
	/* 1 - available, 2 - used */
	`status`			tinyint unsigned default 1,
	/* 1 - available, 2 - used */
	`userStatus`		tinyint unsigned default 1,
	`hash`				varchar(12),
	`redemptionCode`	bigint unsigned,
	`redeemLocationId`	bigint unsigned,
	
	primary key(`id`),
	index(`userId`),
	index(`dealId`),
	unique(`redemptionCode`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealMedia}}`;
CREATE TABLE `{{DealMedia}}` (
	`id`				bigint unsigned not null auto_increment,
	`dealId`			bigint unsigned,
	`type`				tinyint unsigned,
	`data`				text,
	`description`		varchar(250),
	`order`				tinyint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealSubscriptionCampaign}}`;
CREATE TABLE `{{DealSubscriptionCampaign}}` (
	`id`				bigint unsigned,
	`campaignId`		bigint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealCategory}}`;
CREATE TABLE `{{DealCategory}}` (
	`id`				bigint unsigned not null auto_increment,
	`url`				varchar(250),
	`enabled`			tinyint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealCategoryAssoc}}`;
CREATE TABLE `{{DealCategoryAssoc}}` (
	`id`				bigint unsigned not null auto_increment,
	`dealId`			bigint unsigned,
	`categoryId`		bigint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealPrice}}`;
CREATE TABLE `{{DealPrice}}` (
	`id`				bigint unsigned not null auto_increment,
	`dealId`			bigint unsigned,
	`price`				double,
	`value`				double,
	`couponPrice`		double,
	`main`				tinyint unsigned,
	primary key(`id`),
	index(`dealId`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;