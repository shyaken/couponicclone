DROP TABLE IF EXISTS `{{I18N}}`;
CREATE TABLE `{{I18N}}` (
	`id`				bigint unsigned not null auto_increment,
	`model`				varchar(250),
	`relatedId`			bigint unsigned,
	`language`			varchar(10),
	`name`				varchar(250),
	`value`				text,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;