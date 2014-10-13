ALTER TABLE `{{LocationPreset}}` DROP `id`;
ALTER TABLE `{{LocationPreset}}` DROP INDEX `location` , ADD PRIMARY KEY ( `location` );