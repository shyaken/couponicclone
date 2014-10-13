ALTER TABLE `{{Deal}}` ADD `barcode` varchar(250), ADD `priority` tinyint unsigned;

DROP TABLE IF EXISTS `{{DealMedia}}`;
CREATE TABLE `{{DealMedia}}` (
	`id`				bigint unsigned not null auto_increment,
	`dealId`			bigint unsigned,
	`type`				tinyint unsigned,
	`data`				text,
	`order`				tinyint unsigned,
	primary key(`id`)
) engine = MyISAM;