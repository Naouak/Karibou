CREATE TABLE IF NOT EXISTS `onlineusers` (
  `user_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `user_ip` int(10) unsigned NOT NULL,
  `proxy_ip` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8
