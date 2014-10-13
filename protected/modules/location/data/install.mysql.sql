DROP TABLE IF EXISTS `{{Location}}`;
CREATE TABLE `{{Location}}` (
	`id`				bigint unsigned not null auto_increment,
	`country`			varchar(2),
	`state`				varchar(5),
	`city`				varchar(250),
	`cityASCII`			varchar(250),
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{LocationPreset}}`;
CREATE TABLE `{{LocationPreset}}` (
	`location`			bigint unsigned,
	`url`				varchar(250),
	`lon`				double,
	`lat`				double,
	`background`		bigint unsigned,
	primary key(`location`),
	unique(`url`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;