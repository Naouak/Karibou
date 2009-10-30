CREATE TABLE `karibou_apps`.`votes` (
`id` SERIAL NOT NULL ,
`key_id` INT NOT NULL ,
`user` INT NOT NULL ,
`vote` INT NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `votes` ADD UNIQUE (
`key_id` ,
`user`
);

