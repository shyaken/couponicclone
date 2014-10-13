DROP TABLE IF EXISTS `{{Citymanager}}`;
CREATE TABLE `{{Citymanager}}` (
	`id`					bigint unsigned not null auto_increment,
	`userId`				bigint unsigned,
	`location`				bigint unsigned,
	/* 0 - can edit only those deals that belong to the manager, 1 - can edit all deals within allowed city */
	`level`					tinyint unsigned,
	primary key(`id`),
	unique(`userId`, `location`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{CitymanagerItem}}`;
CREATE TABLE `{{CitymanagerItem}}` (
	`id`					bigint unsigned not null auto_increment,
	`userId`				bigint unsigned,
	`itemType`				varchar(250),
	`itemId`				bigint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;