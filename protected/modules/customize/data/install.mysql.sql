DROP TABLE IF EXISTS `{{ThemeColorScheme}}`;
CREATE TABLE `{{ThemeColorScheme}}` (
	`id`				bigint unsigned not null auto_increment,
	`themeId`			varchar(250) not null,
	`name`				varchar(250) not null,
	`current`			tinyint unsigned not null,
	`value`				text not null,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{ThemeImage}}`;
CREATE TABLE `{{ThemeImage}}` (
	`id`				bigint unsigned not null auto_increment,
	`themeId`			varchar(50) not null,
	`name`				varchar(50) not null,
	`value`				blob,
	primary key(`id`),
	unique(`themeId`,`name`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{CmsPage}}`;
CREATE TABLE `{{CmsPage}}` (
	`id`				bigint unsigned not null auto_increment,
	`url`				varchar(250),
	`title`				varchar(250),
	`content`			longtext,
	`mainMenu`			tinyint unsigned,
	`footerMenu`		tinyint unsigned,
	`editorType`		tinyint unsigned,
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `{{CmsBlock}}`;
CREATE TABLE `{{CmsBlock}}` (
	`id`				bigint unsigned not null auto_increment,
	`space`				varchar(250),
	`position`			varchar(250),
	`title`				varchar(250),
	`show`				varchar(250),
	`hide`				varchar(250),
	`content`			text,
	`editorType`		tinyint unsigned,
	`getParams`			varchar(250),
	primary key(`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;