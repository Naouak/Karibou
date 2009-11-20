CREATE TABLE `karibou_apps`.`comment` (
`id` SERIAL NOT NULL ,
`key_id` INT NOT NULL ,
`parent` INT NOT NULL DEFAULT '0',
`user` INT UNSIGNED NOT NULL ,
`date` TIMESTAMP NOT NULL ,
`comment` TEXT NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `karibou_apps`.`combox` (
`id` SERIAL NOT NULL ,
`name` VARCHAR( 60 ) NOT NULL ,
`title` TEXT NOT NULL ,
`content` TEXT NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `karibou_apps`.`combox` ADD UNIQUE (
`name`
);

ALTER TABLE `comment` ADD `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0',
ADD INDEX ( `deleted` ) ;