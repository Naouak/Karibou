-- phpMyAdmin SQL Dump
-- version 2.6.2-pl1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Dimanche 21 Août 2005 à 22:08
-- Version du serveur: 4.1.12
-- Version de PHP: 5.1.0b3
-- 
-- Base de données: `karibou`
-- 

-- --------------------------------------------------------


DROP TABLE IF EXISTS `keychain`;
CREATE TABLE `keychain` (
  `user_id` int(11) NOT NULL default '0',
  `name` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `data` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`user_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        

-- 
-- Structure de la table `onlineusers`
-- 

DROP TABLE IF EXISTS `onlineusers`;
CREATE TABLE `onlineusers` (
  `user_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `connectes`
-- 



-- 
-- Structure de la table `addressbook`
-- 

DROP TABLE IF EXISTS `addressbook`;
CREATE TABLE `addressbook` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `surname` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `birthday` date NOT NULL default '0000-00-00',
  `url` text collate utf8_unicode_ci NOT NULL,
  `note` text collate utf8_unicode_ci NOT NULL,
  `title` text collate utf8_unicode_ci NOT NULL,
  `role` text collate utf8_unicode_ci NOT NULL,
  `org_name` text collate utf8_unicode_ci NOT NULL,
  `org_unit` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Structure de la table `addressbook_address`
-- 

DROP TABLE IF EXISTS `addressbook_address`;
CREATE TABLE `addressbook_address` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `type` varchar(32) collate utf8_unicode_ci NOT NULL default 'DOM',
  `poaddress` text collate utf8_unicode_ci NOT NULL,
  `extaddress` text collate utf8_unicode_ci NOT NULL,
  `street` text collate utf8_unicode_ci NOT NULL,
  `city` text collate utf8_unicode_ci NOT NULL,
  `region` text collate utf8_unicode_ci NOT NULL,
  `postcode` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `country` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Structure de la table `addressbook_email`
-- 

DROP TABLE IF EXISTS `addressbook_email`;
CREATE TABLE `addressbook_email` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `type` varchar(32) collate utf8_unicode_ci NOT NULL default 'AOL',
  `email` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Structure de la table `addressbook_group`
-- 

DROP TABLE IF EXISTS `addressbook_group`;
CREATE TABLE `addressbook_group` (
  `profile_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`profile_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Structure de la table `addressbook_phone`
-- 

DROP TABLE IF EXISTS `addressbook_phone`;
CREATE TABLE `addressbook_phone` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `type` varchar(32) collate utf8_unicode_ci NOT NULL default 'PREF',
  `number` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Structure de la table `addressbook_user`
-- 

DROP TABLE IF EXISTS `addressbook_user`;
CREATE TABLE `addressbook_user` (
  `profile_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`profile_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Structure de la table `minichat`
-- 

DROP TABLE IF EXISTS `minichat`;
CREATE TABLE `minichat` (
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `id_auteur` mediumint(8) unsigned NOT NULL default '0',
  `post` mediumtext NOT NULL,
  PRIMARY KEY  (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `minichat`
-- 

INSERT INTO `minichat` (`time`, `id_auteur`, `post`) VALUES ('2005-05-22 18:19:08', 1, 'hi'),
('2005-06-02 00:44:38', 1, 'ploup'),
('2005-06-02 00:45:05', 1, 'ploup'),
('2005-06-02 19:27:28', 1, 'ploup ploup'),
('2005-07-16 08:56:33', 1, 'ploup'),
('2005-07-16 10:33:14', 1, 'jon'),
('2005-07-16 15:03:52', 1, 'fdgdf'),
('2005-07-16 15:03:55', 1, 'fd'),
('2005-07-16 15:04:00', 1, 'dfgdf'),
('2005-07-16 15:04:06', 1, 'dfgdf'),
('2005-07-16 15:04:10', 1, 'reter'),
('2005-07-16 15:04:14', 1, 'gdfhdfgh'),
('2005-07-16 15:04:18', 1, 'sgh'),
('2005-07-16 15:04:21', 1, 'zare'),
('2005-07-16 15:04:25', 1, 'wxcv'),
('2005-07-16 15:04:31', 1, 'fdg'),
('2005-07-16 15:04:35', 1, 'zer'),
('2005-07-17 11:16:34', 1, 'bb'),
('2005-08-09 21:16:08', 1, 'ber'),
('2005-08-09 21:16:13', 1, 'bou'),
('2005-08-09 21:16:16', 1, 'bou'),
('2005-08-09 21:16:25', 1, 'bou'),
('2005-08-14 18:43:53', 1, 'bah .'),
('2005-08-21 13:10:14', 1, 'jon'),
('2005-08-21 13:10:53', 1, 'jon'),
('2005-08-21 13:11:02', 1, 'jon'),
('2005-08-21 13:11:35', 1, 'jon'),
('2005-08-21 13:20:11', 1, 'rr');

-- --------------------------------------------------------

-- 
-- Structure de la table `news`
-- 

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `i` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL default '0',
  `id_author` int(11) NOT NULL default '0',
  `id_groups` text NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `last` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(4) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`i`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `news`
-- 

INSERT INTO `news` (`i`, `id`, `id_author`, `id_groups`, `title`, `content`, `last`, `deleted`, `time`) VALUES (1, 1, 1, '', 'jon', 'jon', 1, 0, '2005-07-16 09:18:17'),
(2, 2, 1, '', 'jon2', 'jon', 1, 0, '2005-07-16 09:18:45'),
(3, 3, 1, '', 'jonjon', 'jonjonjon', 1, 0, '2005-08-20 13:36:48'),
(4, 4, 1, '', 'jonjon', 'jonjonjon', 1, 0, '2005-08-20 13:39:49');

-- --------------------------------------------------------

-- 
-- Structure de la table `news_comments`
-- 

DROP TABLE IF EXISTS `news_comments`;
CREATE TABLE `news_comments` (
  `id` int(11) NOT NULL auto_increment,
  `id_news` int(11) NOT NULL default '0',
  `id_parent` int(11) NOT NULL default '0',
  `id_author` int(11) NOT NULL default '0',
  `title` text NOT NULL,
  `content` text NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `news_comments`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `permissions_group`
-- 

DROP TABLE IF EXISTS `permissions_group`;
CREATE TABLE `permissions_group` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `appli` varchar(50) NOT NULL default '',
  `permission` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`group_id`,`appli`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `permissions_group`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `permissions_user`
-- 

DROP TABLE IF EXISTS `permissions_user`;
CREATE TABLE `permissions_user` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `appli` varchar(50) NOT NULL default '',
  `permission` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`user_id`,`appli`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `permissions_user`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `prefs`
-- 

DROP TABLE IF EXISTS `prefs`;
CREATE TABLE `prefs` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`user_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure de la table `wiki`
-- 

DROP TABLE IF EXISTS `wiki`;
CREATE TABLE `wiki` (
  `id` int(11) NOT NULL auto_increment,
  `page_name` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL default '0',
  `date` datetime default NULL,  
  `latest` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `wiki`
-- 
INSERT INTO `wiki` ( `id` , `page_name` , `content` , `user_id` , `date` , `latest` )
VALUES (
'', 'Accueil', '!!! Page d''accueil du wiki', '1', '2005-08-29 22:27:27', 'Y'
);


-- 
-- Table structure for table `netcv_resumes`
-- 
DROP TABLE IF EXISTS `netcv_resumes`;
CREATE TABLE `netcv_resumes` (
  `resume_id` int(8) unsigned NOT NULL default '0',
  `id` int(8) unsigned NOT NULL default '0',
  `parent_id` int(8) unsigned default NULL,
  `infos` text,
  `ordering` text,
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modification` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`resume_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `netcv_resumes`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `netcv_resumes_by_lang`
-- 
DROP TABLE IF EXISTS `netcv_resumes_by_lang`;
CREATE TABLE `netcv_resumes_by_lang` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `lang` varchar(5) NOT NULL default '',
  `firstname` text,
  `lastname` text,
  `email` text,
  `address1stline` text,
  `address2ndline` text,
  `addresscitycode` text,
  `addresscity` text,
  `phonehome` text,
  `phonecompany` text,
  `phonemobile` text,
  `birth` text,
  `otherinfos` text,
  `jobtitle` text,
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modification` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `netcv_resumes_by_lang`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `netcv_resumes_group`
-- 
DROP TABLE IF EXISTS `netcv_resumes_group`;
CREATE TABLE `netcv_resumes_group` (
  `id` int(9) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `skin_id` tinyint(4) NOT NULL default '12',
  `name` text NOT NULL,
  `hostname` text NOT NULL,
  `diffusion` enum('public','private','nocrawl') NOT NULL default 'public',
  `emailDisplay` enum('form','show','image') NOT NULL default 'form',
  `description` text NOT NULL,
  `photo` text NOT NULL,
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modification` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `netcv_resumes_group`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `netcv_resumes_stats`
-- 
DROP TABLE IF EXISTS `netcv_resumes_stats`;
CREATE TABLE `netcv_resumes_stats` (
  `num` int(9) NOT NULL auto_increment,
  `resume_id` int(8) unsigned default NULL,
  `RemoteAddress` text,
  `RemoteHost` text,
  `Referer` text,
  `WhereFrom` text,
  `DateTime` datetime default NULL,
  `UserAgent` text,
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `netcv_resumes_stats`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `netcv_skins`
-- 
DROP TABLE IF EXISTS `netcv_skins`;
CREATE TABLE `netcv_skins` (
  `id` int(9) NOT NULL auto_increment,
  `name` text NOT NULL,
  `filename` text NOT NULL,
  `category` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `display` tinyint(1) NOT NULL default '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- 
-- Dumping data for table `netcv_skins`
-- 

INSERT INTO `netcv_skins` VALUES (1, 'Colors', 'default.css', 'Modern', '2003-10-31 00:00:00', 1);
INSERT INTO `netcv_skins` VALUES (5, 'Honey', 'honey.css', 'Modern', '2003-10-31 00:00:00', 1);
INSERT INTO `netcv_skins` VALUES (3, 'Grey', 'classic1.css', 'Modern', '2003-10-31 00:00:00', 1);
INSERT INTO `netcv_skins` VALUES (4, 'Orange', 'orange1.css', 'Modern', '2003-10-31 00:00:00', 1);
INSERT INTO `netcv_skins` VALUES (6, 'Blue Velvet', 'bluevelvet.css', 'Modern', '2003-10-31 00:00:00', 1);
INSERT INTO `netcv_skins` VALUES (11, 'Black &amp; White', 'pro1.css', 'Business', '2003-11-17 01:01:58', 1);
INSERT INTO `netcv_skins` VALUES (12, 'Black &amp; White', 'smart1.css', 'Classic', '2003-12-09 23:19:58', 1);
INSERT INTO `netcv_skins` VALUES (7, 'Centered', 'classic1md.css', 'Modern', '2004-05-28 14:11:00', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `netcv_users`
-- 
DROP TABLE IF EXISTS `netcv_users`;
CREATE TABLE `netcv_users` (
  `id` int(11) NOT NULL auto_increment,
  `username` text NOT NULL,
  `firstname` text,
  `lastname` text,
  `email` text,
  `address1stline` text,
  `address2ndline` text,
  `addresscitycode` text,
  `addresscity` text,
  `phonehome` text,
  `phonecompany` text,
  `phonemobile` text,
  `birth` text,
  `otherinfos` text,
  `jobtitle` text,
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modification` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `flashmail`;
CREATE TABLE `flashmail` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `omsgid` int(11) NOT NULL default '0',
  `from_user_id` int(11) NOT NULL default '0',
  `to_user_id` int(11) NOT NULL default '0',
  `message` text collate utf8_unicode_ci NOT NULL,
  `read` tinyint(4) NOT NULL default '0',
  `archive` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Structure de la table `calendar`
-- 

DROP TABLE IF EXISTS `calendar`;
CREATE TABLE `calendar` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `type` enum('public', 'private') NOT NULL default 'public',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Contenu de la table `calendar`
-- 

INSERT INTO `calendar` VALUES (1, 'jonjoncal', 'public');

-- --------------------------------------------------------

-- 
-- Structure de la table `calendar_event`
-- 

DROP TABLE IF EXISTS `calendar_event`;
CREATE TABLE `calendar_event` (
  `id` int(11) NOT NULL auto_increment,
  `calendar_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `description` text collate utf8_unicode_ci NOT NULL,
  `summary` text collate utf8_unicode_ci NOT NULL,
  `location` text collate utf8_unicode_ci NOT NULL,
  `category` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `priority` tinyint(4) NOT NULL default '0',
  `startdate` datetime default '0000-00-00 00:00:00',
  `stopdate` datetime default '0000-00-00 00:00:00',
  `recurrence` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

-- 
-- Structure de la table `calendar_group`
-- 

DROP TABLE IF EXISTS `calendar_group`;
CREATE TABLE `calendar_group` (
  `group_id` int(11) NOT NULL default '0',
  `calendar_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`calendar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Contenu de la table `calendar_group`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `calendar_user`
-- 

DROP TABLE IF EXISTS `calendar_user`;
CREATE TABLE `calendar_user` (
  `user_id` int(11) NOT NULL default '0',
  `calendar_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`calendar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Contenu de la table `calendar_user`
-- 

INSERT INTO `calendar_user` VALUES (1, 1);

-- --------------------------------------------------------


CREATE TABLE `admin_import` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `group` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `login` text NOT NULL,
  `imported_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `emailsent_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Structure de la table `fileshare_rights`
-- 

CREATE TABLE `fileshare_rights` (
  `id` int(11) NOT NULL default '0',
  `group` int(11) default '0',
  `rights` int(11) NOT NULL default '0',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `fileshare_sysinfos`
-- 

CREATE TABLE `fileshare_sysinfos` (
  `id` int(11) NOT NULL default '0',
  `parent` int(11) default '0',
  `name` text NOT NULL,
  `creator` int(11) NOT NULL default '0',
  `groupowner` int(11) default '0',
  `type` enum('file','folder') NOT NULL default 'file',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `fileshare_versions`
-- 

CREATE TABLE `fileshare_versions` (
  `id` int(11) NOT NULL default '0',
  `versionid` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `user` int(11) NOT NULL default '0',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

