DROP TABLE IF EXISTS `{{LocationPreset}}`;
CREATE TABLE `{{LocationPreset}}` (
	`id`				bigint unsigned not null auto_increment,
	`location`			bigint unsigned,
	`url`				varchar(250),
	`lon`				double,
	`lat`				double,
	`background`		bigint unsigned,
	primary key(`id`),
	unique(`location`),
	unique(`url`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{LocationI18N}}`;
CREATE TABLE `{{LocationI18N}}` (
	`id`				bigint unsigned not null auto_increment,
	`locationId`		bigint unsigned,
	`language`			varchar(10),
	`name`				varchar(250),
	`value`				text,
	primary key(`id`),
	unique(`locationId`,`language`,`name`)	
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;