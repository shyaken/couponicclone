DROP TABLE IF EXISTS `{{DealSubscriptionCampaign}}`;
CREATE TABLE `{{DealSubscriptionCampaign}}` (
	`id`			bigint unsigned,
	`campaignId`		bigint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{DealI18N}}`;
CREATE TABLE `{{DealI18N}}` (
	`id`				bigint unsigned not null auto_increment,
	`dealId`			bigint unsigned,
	`language`			varchar(10),
	`name`				varchar(250),
	`value`				text,
	primary key(`id`),
	unique(`dealId`,`language`,`name`)	
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;