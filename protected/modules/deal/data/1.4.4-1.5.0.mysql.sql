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

ALTER TABLE `{{Deal}}` ADD `useCredits` tinyint;
ALTER TABLE `{{Deal}}` ADD `requireRedeemLoc` tinyint;
ALTER TABLE `{{Deal}}` ADD `statsAdjust` int;
ALTER TABLE `{{DealCoupon}}` ADD `redeemLocationId` bigint;
ALTER TABLE `{{DealCoupon}}` ADD `priceId` bigint unsigned AFTER `dealId`;

UPDATE `{{Deal}}` SET `useCredits` = 1;
UPDATE `{{Deal}}` SET `url` = `id` WHERE `url` IS NULL OR `url` = '';

ALTER TABLE `{{Deal}}` ADD UNIQUE (`url`);
ALTER TABLE `{{DealMedia}}` ADD `description` VARCHAR( 250 );