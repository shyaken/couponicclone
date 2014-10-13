DROP TABLE IF EXISTS `{{TransactionHistory}}`;
CREATE TABLE `{{TransactionHistory}}` (
	`id`					bigint unsigned not null auto_increment,
	`userId`				bigint unsigned,
	`amount`				double,
	`date`					bigint unsigned,
	`comment`				text,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;