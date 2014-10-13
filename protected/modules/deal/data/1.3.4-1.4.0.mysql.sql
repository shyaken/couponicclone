ALTER TABLE `{{Deal}}` ADD `background` bigint unsigned;
ALTER TABLE `{{DealCoupon}}` ADD `redemptionCode` bigint unsigned;
ALTER TABLE `{{DealCoupon}}` ADD UNIQUE (`redemptionCode`);

DROP TABLE IF EXISTS `{{DealCategory}}`;
CREATE TABLE `{{DealCategory}}` (
	`id`				bigint unsigned not null auto_increment,
	`name`				varchar(250),
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