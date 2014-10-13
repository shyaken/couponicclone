DROP TABLE IF EXISTS `{{Company}}`;
CREATE TABLE `{{Company}}` (
	`id`				bigint unsigned not null auto_increment,
	`userId`			bigint unsigned,
	`name`				varchar(250),
	`website`			varchar(250),
	`location`			bigint unsigned,
	`zipCode`			varchar(15),
	`address`			text,
	`phone`				varchar(250),
	`payment`			varchar(250),
	`commission`		double unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;