ALTER TABLE `{{User}}` CHANGE `name` `firstName` varchar(250);
ALTER TABLE `{{User}}` ADD `lastName` varchar(250) AFTER `firstName`;
UPDATE `{{User}}` SET `lastName` = SUBSTRING_INDEX(`firstName`,' ',-1),
	`firstName` = SUBSTRING_INDEX(`firstName`,' ',1)
	 WHERE `firstName` LIKE '% %';