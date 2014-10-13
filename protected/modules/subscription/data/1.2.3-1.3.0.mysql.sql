DROP TABLE IF EXISTS `{{SubscriptionCampaignList}}`;
CREATE TABLE `{{SubscriptionCampaignList}}` (
	`id`					bigint unsigned not null auto_increment,
	`campaignId`			bigint unsigned,
	`listId`				bigint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;