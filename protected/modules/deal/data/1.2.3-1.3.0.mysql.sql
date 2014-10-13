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

ALTER TABLE `{{Deal}}` ADD `commission` DOUBLE UNSIGNED;
ALTER TABLE `{{Deal}}` ADD `paymentOptions` VARCHAR( 250 ); 
ALTER TABLE `{{DealCoupon}}` ADD `userStatus` TINYINT UNSIGNED DEFAULT 1;
ALTER TABLE `{{DealCoupon}}` ADD `hash` VARCHAR( 12 );
ALTER TABLE `{{DealCoupon}}` ADD UNIQUE (`hash`);
UPDATE `{{DealCoupon}}` SET `hash` = `id` WHERE `hash` IS NULL OR `hash` = '';