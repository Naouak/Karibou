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

ALTER TABLE `pictures_album` CHANGE `name` `name` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `pictures_album` ADD `type` ENUM( "carton", "album" ) NOT NULL;

ALTER TABLE `pictures_album` ADD `parent` INT NULL;



ALTER TABLE `pictures_album` ADD UNIQUE (
`name` ,
`parent`
);

CREATE TABLE `karibou_apps`.`pictures_album_acl` (
`id_album` INT NOT NULL ,
`group` INT NULL ,
`user` INT NULL ,
`droit` ENUM( "read", "write" ) NOT NULL ,
`id` SERIAL NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `karibou_apps`.`pictures_album_tagged` (
`id_album` INT NOT NULL ,
`id_tag` INT NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `pictures_tags` CHANGE `name` `name` VARCHAR( 56 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;

ALTER TABLE `pictures_tags` ADD UNIQUE (
`name`
);

ALTER TABLE `pictures_album_tagged` ADD UNIQUE (
`id_album` ,
`id_tag`
);

 ALTER TABLE `pictures_tags` ADD UNIQUE (
`name`
) ;
