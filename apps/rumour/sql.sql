CREATE TABLE `karibou_apps`.`rumours` (
`id` SERIAL NOT NULL ,
`rumours` TEXT NOT NULL ,
`date` TIMESTAMP NOT NULL
`deleted` TINYINT(1) NOT NULL DEFAULT 0,
) ENGINE = InnoDB;
