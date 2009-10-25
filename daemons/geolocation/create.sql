--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(11) NOT NULL auto_increment,
  `user_ip` int(10) unsigned NOT NULL,
  `proxy_ip` int(10) unsigned default NULL,
  `user_mask` int(10) unsigned NOT NULL,
  `proxy_mask` int(10) unsigned default NULL,
  `location` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `onlineusers`
--

CREATE TABLE IF NOT EXISTS `onlineusers` (
  `user_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL default '0',
  `user_ip` int(10) unsigned NOT NULL,
  `proxy_ip` int(10) unsigned default NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;
