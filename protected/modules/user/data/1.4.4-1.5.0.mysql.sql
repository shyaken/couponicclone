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