-- phpMyAdmin SQL Dump
-- version 2.6.2-pl1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Dimanche 21 Août 2005 à 22:12
-- Version du serveur: 4.1.12
-- Version de PHP: 5.1.0b3
-- 
-- Base de données: `annuaire`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `group_user`
-- 

DROP TABLE IF EXISTS `group_user`;
CREATE TABLE `group_user` (
  `user_id` mediumint(9) unsigned NOT NULL default '0',
  `group_id` tinyint(3) unsigned NOT NULL default '0',
  `role` enum('member','admin') NOT NULL default 'member',
  `visibility` enum('visible','hidden') NOT NULL default 'visible',
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- 
-- Contenu de la table `group_user`
-- 

INSERT INTO `group_user` (`user_id`, `group_id`) VALUES (1, 3),
(1, 5),
(1, 7),
(2, 5),
(3, 2),
(3, 5),
(4, 2),
(4, 7);

-- --------------------------------------------------------

-- 
-- Structure de la table `groups`
-- 

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(30) default '0',
  `url` varchar(100) default NULL,
  `ml` varchar(100) default NULL,
  `description` varchar(100) default NULL,
  `left` int(11) NOT NULL default '0',
  `right` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `groups`
-- 

INSERT INTO `groups` (`id`, `name`, `url`, `ml`, `description`, `left`, `right`) VALUES (1, 'Promotions', NULL, NULL, NULL, 1, 10),
(2, 'Fi04', NULL, NULL, NULL, 2, 3),
(3, 'Fi05', NULL, NULL, NULL, 4, 5),
(4, 'Clubs', NULL, NULL, NULL, 11, 20),
(5, 'Intranet', NULL, NULL, NULL, 12, 17),
(6, 'Webenic', NULL, NULL, NULL, 18, 19),
(7, 'Moderators', NULL, NULL, NULL, 13, 14),
(8, 'test', NULL, NULL, NULL, 15, 16),
(9, 'Fi03', NULL, NULL, NULL, 6, 7),
(10, 'Fi06', NULL, NULL, NULL, 8, 9),
(11, 'New', NULL, NULL, NULL, 21, 22);

-- --------------------------------------------------------

-- 
-- Structure de la table `profile`
-- 

DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
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

-- 
-- Contenu de la table `profile`
-- 

INSERT INTO `profile` (`id`, `firstname`, `lastname`, `surname`, `birthday`, `url`, `note`, `title`, `role`, `org_name`, `org_unit`) VALUES (1, 'Jonathan', 'SEMCZYK', 'JoN', '0000-00-00', '', 'partagez !', '', '', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `profile_address`
-- 

DROP TABLE IF EXISTS `profile_address`;
CREATE TABLE `profile_address` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `type`  varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `poaddress` text collate utf8_unicode_ci NOT NULL,
  `extaddress` text collate utf8_unicode_ci NOT NULL,
  `street` text collate utf8_unicode_ci NOT NULL,
  `city` text collate utf8_unicode_ci NOT NULL,
  `region` text collate utf8_unicode_ci NOT NULL,
  `postcode` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `country` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Contenu de la table `profile_address`
-- 

INSERT INTO `profile_address` (`id`, `profile_id`, `type`, `poaddress`, `extaddress`, `street`, `city`, `region`, `postcode`, `country`) VALUES (13, 1, 'HOME', 'Jonathan SEMCZYK', '', '57 rue Jeanne d''Arc', 'Villeneuve d''Ascq', 'Nord', '59650', 'France'),
(2, 0, 'DOM', 'ee', 'ee', 'ee', 'ee', 'ee', 'ee', 'ee');

-- --------------------------------------------------------

-- 
-- Structure de la table `profile_email`
-- 

DROP TABLE IF EXISTS `profile_email`;
CREATE TABLE `profile_email` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `type`  varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `email` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Contenu de la table `profile_email`
-- 

INSERT INTO `profile_email` (`id`, `profile_id`, `type`, `email`) VALUES (4, 1, 'INTERNET', 'jonathan.semczyk@telecomlille.net');

-- --------------------------------------------------------

-- 
-- Structure de la table `profile_phone`
-- 

DROP TABLE IF EXISTS `profile_phone`;
CREATE TABLE `profile_phone` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `type`  varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `number` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Contenu de la table `profile_phone`
-- 

INSERT INTO `profile_phone` (`id`, `profile_id`, `type`, `number`) VALUES (1, 1, 'WORK', '+1 781 238 1767');

-- --------------------------------------------------------

-- 
-- Structure de la table `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `login` varchar(64) NOT NULL default '',
  `password` varchar(64) character set utf8 collate utf8_bin default NULL,
  `profile_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `loginEtudiant` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `users`
-- 

INSERT INTO `users` (`id`, `login`, `password`, `profile_id`) VALUES (1, 'jon', PASSWORD('jon') , 1),
(2, 'datoine', PASSWORD('datoine') , 0),
(3, 'test', PASSWORD('test') , 0),
(4, 'demo', PASSWORD('demo') , 0),
(5, 'mcben', PASSWORD('pouet') , 0);
