DROP TABLE IF EXISTS `{{User}}`;
CREATE TABLE `{{User}}` (
	`id`				bigint unsigned not null auto_increment,
	`email`				varchar(250),
	/* salted-md5-encrypted password */
	`password`			varchar(32),
	/* password salt */
	`salt`				char(3),
	/* 0 - nothing, 1 - user password needs to be updated */
	`changePassword`	tinyint unsigned,
	`role`				varchar(50) not null default 'user',
	`created`			bigint unsigned,
	`firstName`			varchar(250),
	`lastName`			varchar(250),
	`avatar`			bigint unsigned,
	`timeZone`			double default 0,
	`ip`				varchar(250),
	`language`			varchar(10),
	
	primary key (`id`),
	unique(`email`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci ;

DROP TABLE IF EXISTS `{{UserProfile}}`;
CREATE TABLE IF NOT EXISTS `{{UserProfile}}` (
	`id`					bigint unsigned not null auto_increment,
	`value`					text,
	`userId`				bigint unsigned,
	`settingId`				bigint unsigned,

	PRIMARY KEY (`id`),
	UNIQUE(`userId`, `settingId`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci ;

DROP TABLE IF EXISTS `{{UserProfileSetting}}`;
CREATE TABLE IF NOT EXISTS `{{UserProfileSetting}}` (
	`id`					bigint unsigned not null auto_increment,
	`label`					varchar(250),
	`type`					varchar(50),
	`rules`					varchar(250),
	`items`					text,

	PRIMARY KEY (`id`)
) engine = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci ;