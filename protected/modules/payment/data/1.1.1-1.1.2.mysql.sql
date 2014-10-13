DROP TABLE IF EXISTS `{{PaymentCredit}}`;
CREATE TABLE `{{PaymentCredit}}` (
	`id`					bigint unsigned,
	`amount`				double unsigned,
	primary key(`id`)
) engine = MyISAM;

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
) engine = MyISAM;

DROP TABLE IF EXISTS `{{PaymentOrderItem}}`;
CREATE TABLE `{{PaymentOrderItem}}` (
	`id`					bigint unsigned not null auto_increment,
	`orderId`				bigint unsigned,
	`itemModule`			varchar(250),
	`itemId`				bigint unsigned,
	`quantity`				int unsigned,
	primary key(`id`)
) engine = MyISAM;