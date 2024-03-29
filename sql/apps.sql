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
  `user_ip` int(32) NOT NULL,
  `proxy_ip` int(32) NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `connectes`
-- 

--
-- Contenu de la table `location`
--
DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
    `location_id` int(32) NOT NULL,
    `user_ip` int(32) NOT NULL,
    `location` varchar(64) collate utf8_unicode_ci NOT NULL default '',
    `proxy_ip` int(32) NULL,
    `proxy_mask` int(32) NULL,
    `user_mask` int(32) NOT NULL,
    PRIMARY KEY (`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

INSERT INTO `minichat` (`time`, `id_auteur`, `post`) VALUES ('2005-05-22 18:19:08', 1, 'Bienvenue sur Karibou');

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


INSERT INTO `news` (`i`, `id`, `id_author`, `id_groups`, `title`, `content`, `last`, `deleted`, `time`) VALUES 
(1, 1, 1, '', 'Félicitations : l''Intranet est installé !', 'Félicitations !\r\n\r\nL''Intranet a été installé avec succès !\r\n\r\nTu peux accéder aux permissions des applications sur : [http://127.0.0.1/permissions/]\r\n\r\nL''application d''administration est disponible sur [http://127.0.0.1/admin/] (�  finaliser)\r\n\r\nPHPMyAdmin est disponible sur [http://127.0.0.1/phpmyadmin/] (disponible en fonction de l''installation)\r\n\r\nBon courage pour le développement !\r\n\r\nL''ékip', 1, 0, '2007-03-16 02:26:17');

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
INSERT INTO `news_comments` (`id`, `id_news`, `id_parent`, `id_author`, `title`, `content`, `time`) VALUES 
(1, 1, 0, 1, 'Un conseil pour les utilisateurs de subversion sous Windows', 'TortoiseSVN est un des meilleurs clients subversion qui tourne sous Windows ([http://tortoisesvn.tigris.org/]).', '2007-03-16 02:24:46'),
(2, 1, 0, 1, 'Un autre conseil pour ceux sous linux', 'RapidSVN est pas mal non plus ! ([http://rapidsvn.tigris.org/])', '2007-03-16 02:25:57');

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

INSERT INTO `permissions_group` (`group_id`, `appli`, `permission`) VALUES 
(1, 'permissions', '_ADMIN_'),
(1, 'admin', '_ADMIN_');

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

--
DROP TABLE IF EXISTS `calendar_colors`;
CREATE TABLE `calendar_colors` (
  `calendarid` int(11) NOT NULL default '0',
  `color1` varchar(6) NOT NULL default '',
  `color2` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`calendarid`),
  UNIQUE KEY `calendarid` (`calendarid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `calendar_colors`
-- 

INSERT INTO `calendar_colors` (`calendarid`, `color1`, `color2`) VALUES (1, '59ffe1', '98fdeb'),
(2, 'ffee53', 'fff494'),
(3, '786cff', 'b1a9ff'),
(4, '64ef69', '98fd9b'),
(5, 'ff4d59', 'ff858d');



-- --------------------------------------------------------

DROP TABLE IF EXISTS `admin_import`;
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

DROP TABLE IF EXISTS `fileshare_rights`;
CREATE TABLE `fileshare_rights` (
  `id` int(11) NOT NULL default '0',
  `group` int(11) default '0',
  `rights` int(11) NOT NULL default '0',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `fileshare_stats`
-- 

DROP TABLE IF EXISTS `fileshare_stats`;
CREATE TABLE `fileshare_stats` (
  `id` int(11) NOT NULL auto_increment,
  `elementid` int(11) NOT NULL default '0',
  `versionid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `fileshare_sysinfos`
-- 

DROP TABLE IF EXISTS `fileshare_sysinfos`;
CREATE TABLE `fileshare_sysinfos` (
  `id` int(11) NOT NULL default '0',
  `parent` int(11) default '0',
  `name` text NOT NULL,
  `creator` int(11) NOT NULL default '0',
  `groupowner` int(11) default '0',
  `type` enum('file','folder') NOT NULL default 'file',
  `deleted` tinyint(1) NOT NULL default '0',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `fileshare_sysinfos`  ENGINE = MYISAM ;

ALTER TABLE `fileshare_sysinfos` ADD FULLTEXT (
`name`
);

-- --------------------------------------------------------

-- 
-- Structure de la table `fileshare_versions`
-- 

DROP TABLE IF EXISTS `fileshare_versions`;
CREATE TABLE `fileshare_versions` (
  `id` int(11) NOT NULL default '0',
  `versionid` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `uploadname` text NOT NULL,
  `user` int(11) NOT NULL default '0',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `fileshare_versions`  ENGINE = MYISAM ;

ALTER TABLE `fileshare_versions` ADD FULLTEXT (
`description`
);

DROP TABLE IF EXISTS `netjobs_companies`;
CREATE TABLE `netjobs_companies` (
  `i` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL default '0',
  `last` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `description` text NOT NULL,
  `type` varchar(30) NOT NULL default '',
  `activity` varchar(30) NOT NULL default '',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`i`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `netjobs_contacts`;
CREATE TABLE `netjobs_contacts` (
  `contact_id` int(11) NOT NULL default '0',
  `id` int(11) NOT NULL default '0',
  `type` enum('job','company') NOT NULL default 'job',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `netjobs_jobs`;
CREATE TABLE `netjobs_jobs` (
  `i` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL default '0',
  `last` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `title` text NOT NULL,
  `description` text NOT NULL,
  `profile` text NOT NULL,
  `type` text NOT NULL,
  `education` varchar(20) NOT NULL default '',
  `role` varchar(30) NOT NULL default '',
  `experience_required` text NOT NULL,
  `salary` int(11) NOT NULL default '0',
  `company_id` int(11) NOT NULL default '0',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`i`),
  FULLTEXT KEY `SearchIndex` (`title`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `netjobs_locations`;
CREATE TABLE `netjobs_locations` (
  `id` int(11) NOT NULL default '0',
  `type` enum('job','company') NOT NULL default 'job',
  `country_id` int(11) NOT NULL default '0',
  `county_id` int(11) NOT NULL default '0',
  `department_id` int(11) NOT NULL default '0',
  `city_id` int(11) NOT NULL default '0',
  `country_name` text NOT NULL,
  `county_name` text NOT NULL,
  `department_name` text NOT NULL,
  `city_name` text NOT NULL,
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `survey_answers` (
  `i` int(11) NOT NULL auto_increment,
  `surveyid` int(11) NOT NULL default '0',
  `questionid` int(11) NOT NULL default '0',
  `versionid` int(11) NOT NULL default '0',
  `last` tinyint(1) NOT NULL default '0',
  `value` text NOT NULL,
  `userid` int(11) NOT NULL default '0',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`i`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

CREATE TABLE `survey_questions` (
  `id` int(11) NOT NULL auto_increment,
  `surveyid` int(11) NOT NULL default '0',
  `type` enum('text','numeric','date') NOT NULL default 'text',
  `name` text NOT NULL,
  `description` text NOT NULL,
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`,`surveyid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `survey_surveys`
-- 

CREATE TABLE `survey_surveys` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `userid` int(11) NOT NULL default '0',
  `datetime` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


CREATE TABLE `forum_forums` (
  `id` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `description` text NOT NULL,
  `userid` text NOT NULL,
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `forum_forums` VALUES (1, 'Test Forum', 'This is the Test Forum', '1', '0000-00-00 00:00:00');


CREATE TABLE `forum_messages` (
  `id` int(11) NOT NULL default '0',
  `forumid` int(11) NOT NULL default '0',
  `originalmessageid` int(11) default NULL,
  `replymessageid` int(11) default NULL,
  `last` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `subject` text NOT NULL,
  `description` text NOT NULL,
  `userid` int(11) NOT NULL default '0',
  `datetime` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- 
-- Structure de la table `dday`
-- 

CREATE TABLE `dday` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `event` mediumtext collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
