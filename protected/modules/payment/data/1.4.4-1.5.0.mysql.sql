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