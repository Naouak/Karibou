CREATE TABLE `karibou_apps`.`votes` (
`id` SERIAL NOT NULL ,
`key_id` BIGINT UNSIGNED NOT NULL ,
`user` INT NOT NULL ,
`vote` INT NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `votes` ADD UNIQUE (
`key_id` ,
`user`
);

ALTER TABLE `votes` ADD FOREIGN KEY ( `key_id` ) REFERENCES `karibou_apps`.`combox` (
	`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

