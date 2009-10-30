CREATE TABLE  `karibou_apps`.`pictures_album` (
`name` TEXT NOT NULL ,
`date` TIMESTAMP NOT NULL ,
`id` SERIAL NOT NULL
) ENGINE = INNODB;


CREATE TABLE  `karibou_apps`.`pictures` (
`id` SERIAL NOT NULL ,
`album` INT NOT NULL ,
`date` TIMESTAMP NOT NULL
) ENGINE = INNODB;

CREATE TABLE  `karibou_apps`.`pictures_tags` (
`id` SERIAL NOT NULL ,
`name` TEXT NOT NULL
) ENGINE = INNODB;

CREATE TABLE  `karibou_apps`.`pictures_tagged` (
`pict` INT NOT NULL ,
`tag` INT NOT NULL
) ENGINE = INNODB;
