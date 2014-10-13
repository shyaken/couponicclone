DROP TABLE IF EXISTS `{{PaymentCredit}}`;
CREATE TABLE `{{PaymentCredit}}` (
	`id`					bigint unsigned,
	`amount`				double unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{PaymentOrder}}`;
CREATE TABLE `{{PaymentOrder}}` (
	`id`					bigint unsigned not null auto_increment,
	`userId`				bigint unsigned,
	`created`				bigint unsigned,
	`amount`				double unsigned,
	/* 0 - placed, 1 - authorized, 2 - charged, 3 - voided */
	`status`				tinyint unsigned default 0,
	`method`				varchar(250),
	`custom`				varchar(250),
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{PaymentOrderItem}}`;
CREATE TABLE `{{PaymentOrderItem}}` (
	`id`					bigint unsigned not null auto_increment,
	`orderId`				bigint unsigned,
	`itemModule`			varchar(250),
	`itemId`				bigint unsigned,
	`quantity`				double unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{TransactionHistory}}`;
CREATE TABLE `{{TransactionHistory}}` (
	`id`					bigint unsigned not null auto_increment,
	`userId`				bigint unsigned,
	`amount`				double,
	`date`					bigint unsigned,
	`comment`				text,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{PaymentOrderOptions}}`;
CREATE TABLE `{{PaymentOrderOptions}}` (
	`id`					bigint unsigned not null auto_increment,
	`itemId`				bigint unsigned,
	`type`					varchar(250),
	`name`					varchar(250),
	`value`					text,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{PaymentAffiliate}}`;
CREATE TABLE `{{PaymentAffiliate}}` (
	`id`					bigint unsigned not null auto_increment,
	`name`					varchar(250),
	`code`					text,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;