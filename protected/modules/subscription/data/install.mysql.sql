DROP TABLE IF EXISTS `{{SubscriptionEmail}}`;
CREATE TABLE `{{SubscriptionEmail}}` (
	`id`					bigint unsigned not null auto_increment,
	`email`					varchar(250),
	`hash`					varchar(250),
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{SubscriptionList}}`;
CREATE TABLE `{{SubscriptionList}}` (
	`id`					bigint unsigned not null auto_increment,
	/* 0 - city list, 1 - deal list, 2 - category list, 100 - all subscribers */
	`type`					tinyint unsigned,
	`relatedId`				bigint unsigned,
	`title`					varchar(250),
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{SubscriptionListEmail}}`;
CREATE TABLE `{{SubscriptionListEmail}}` (
	`id`					bigint unsigned not null auto_increment,
	`listId`				bigint unsigned,
	`emailId`				bigint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{SubscriptionCampaign}}`;
CREATE TABLE `{{SubscriptionCampaign}}` (
	`id`					bigint unsigned not null auto_increment,
	`subject`				varchar(250),
	`htmlBody`				text,
	`plainBody`				text,
	`schedule`				bigint unsigned,
	`complete`				bigint not null default 0,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{SubscriptionCampaignList}}`;
CREATE TABLE `{{SubscriptionCampaignList}}` (
	`id`					bigint unsigned not null auto_increment,
	`campaignId`			bigint unsigned,
	`listId`				bigint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;