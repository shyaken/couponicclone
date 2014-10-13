DROP TABLE IF EXISTS `{{SubscriptionEmail}}`;
CREATE TABLE `{{SubscriptionEmail}}` (
	`id`					bigint unsigned not null auto_increment,
	`email`					varchar(250),
	`hash`					varchar(250),
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE `{{SubscriptionListEmail}}` ADD `emailId` bigint unsigned;
ALTER TABLE `{{SubscriptionListEmail}}` CHANGE `email` `emailBackup` varchar(250);